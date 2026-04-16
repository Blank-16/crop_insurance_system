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
        })->count();
        $claimsCount = Claim::whereHas('policy.plan', function($q) use ($user) {
            $q->where('proposer_id', $user->id);
        })->count();

        return view('proposer.dashboard', compact('plansCount', 'policiesCount', 'claimsCount'));
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

    public function updateClaimStatus(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected,Under Review,field_verification',
            'remarks' => 'required_if:status,Rejected|nullable|string'
        ]);

        $claim->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        // Claim Timeline (Log)
        \App\Models\ClaimLog::create([
            'claim_id' => $claim->id,
            'status' => $request->status,
        ]);

        // Audit Log & Notification for terminal states
        if (in_array($request->status, ['Approved', 'Rejected'])) {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => "{$request->status} Claim",
                'target_id' => $claim->id,
            ]);

            \App\Models\Notification::create([
                'user_id' => $claim->policy->farmer_id,
                'message' => "Your claim #{$claim->id} has been {$request->status}.",
            ]);
        }

        return back()->with('success', 'Claim status updated successfully.');
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
