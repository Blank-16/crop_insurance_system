<?php

namespace App\Http\Controllers;

use App\Models\InsurancePlan;
use App\Models\Policy;
use App\Models\Claim;
use Illuminate\Http\Request;

class ProposerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $plansCount = $user->insurancePlans()->count();

        $policiesCount = Policy::whereHas('plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->where('status', 'active')->count();

        $pendingPoliciesCount = Policy::whereHas('plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->where('status', 'pending')->count();

        $claimsCount = Claim::whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->count();

        $approvedClaimsCount = Claim::whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->where('status', 'Approved')->count();

        $pendingClaimsCount = Claim::whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->whereIn('status', ['Submitted', 'Under Review', 'field_verification'])->count();

        // Chart Data: Claim Trends (Last 6 Months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $claimsTrend = Claim::whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->where('created_at', '>=', $sixMonthsAgo)
          ->select(\DB::raw('DATE_FORMAT(created_at, "%b") as month'), \DB::raw('count(*) as total'))
          ->groupBy('month')
          ->orderBy(\DB::raw('MIN(created_at)'))
          ->pluck('total', 'month')->toArray();
        
        $chartData = [
            'trend_labels' => array_keys($claimsTrend),
            'trend_series' => array_values($claimsTrend),
        ];

        // Chart Data: Portfolio Distribution (Policies by Crop)
        $portfolioDistribution = Policy::whereHas('plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->join('insurance_plans', 'policies.plan_id', '=', 'insurance_plans.id')
          ->select('insurance_plans.crop_type', \DB::raw('count(*) as total'))
          ->groupBy('insurance_plans.crop_type')
          ->pluck('total', 'insurance_plans.crop_type')->toArray();

        $chartData['portfolio_labels'] = array_keys($portfolioDistribution);
        $chartData['portfolio_series'] = array_values($portfolioDistribution);

        return view('proposer.dashboard', compact(
            'plansCount', 'policiesCount', 'pendingPoliciesCount',
            'claimsCount', 'approvedClaimsCount', 'pendingClaimsCount', 'chartData'
        ));
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
        ]);

        auth()->user()->proposerProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['company_name' => $request->company_name]
        );

        return redirect()->route('profile.edit')->with('status', 'proposer-profile-updated');
    }

    public function claims()
    {
        $user = auth()->user();
        $claims = Claim::with(['policy.farmer', 'policy.plan'])->whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->get();

        return view('proposer.claims', compact('claims'));
    }

    public function showClaim(Claim $claim)
    {
        $claim->load(['policy.plan', 'policy.farmer', 'documents', 'logs' => function($q) {
            $q->orderBy('created_at', 'asc');
        }]);

        if ($claim->policy->plan->proposer_id !== auth()->id()) {
            abort(403);
        }

        return view('farmer.claims.show', compact('claim')); // Reusing the visual show page
    }

    public function updateClaimStatus(Request $request, Claim $claim, \App\Services\ClaimService $claimService)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected,Under Review,field_verification',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            $claimService->updateStatus($claim, $request->status, $request->remarks, auth()->id());
            return back()->with('success', 'Claim status updated successfully.');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Please provide a reason for rejection.') {
                return back()->withErrors(['remarks' => $e->getMessage()])->withInput();
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function policies()
    {
        $user = auth()->user();
        $policies = Policy::with(['farmer', 'plan'])->whereHas('plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->latest()->paginate(10);

        return view('proposer.policies', compact('policies'));
    }

    public function updatePolicyStatus(Request $request, Policy $policy)
    {
        $request->validate(['status' => 'required|in:active,rejected']);
        
        $data = ['status' => $request->status];

        if ($request->status === 'active') {
            $data['start_date'] = now();
            // Assuming duration is in months from the plan
            $data['end_date'] = now()->addMonths($policy->plan->duration);
        }

        $policy->update($data);

        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => "{$request->status} Policy",
            'target_id' => $policy->id,
        ]);

        \App\Models\Notification::create([
            'user_id' => $policy->farmer_id,
            'message' => "Your policy application for plan #{$policy->plan->id} has been {$request->status}.",
        ]);

        return back()->with('success', 'Policy status updated successfully.');
    }
}
