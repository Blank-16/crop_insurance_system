<?php

namespace App\Http\Controllers;

use App\Models\InsurancePlan;
use App\Models\Policy;
use App\Models\FarmerProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $policiesCount = $user->policies()->count();
        $activePoliciesCount = $user->policies()->where('status', 'active')->count();
        $claimsCount = \App\Models\Claim::whereHas('policy', function($q) use ($user) {
            $q->where('farmer_id', $user->id);
        })->count();
        $approvedClaimsCount = \App\Models\Claim::whereHas('policy', function($q) use ($user) {
            $q->where('farmer_id', $user->id);
        })->where('status', 'Approved')->count();

        return view('farmer.dashboard', compact('policiesCount', 'activePoliciesCount', 'claimsCount', 'approvedClaimsCount'));
    }

    public function completeProfile()
    {
        $profile = auth()->user()->farmerProfile;
        return view('farmer.profile', compact('profile'));
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'land_size' => 'required|string',
            'crop_type' => 'required|string',
            'region' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        auth()->user()->farmerProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only(['land_size', 'crop_type', 'region', 'latitude', 'longitude'])
        );

        return back()->with('status', 'farmer-profile-updated')->with('success', 'Profile updated effectively.');
    }

    public function plans(Request $request)
    {
        $query = InsurancePlan::query()->with('proposer');

        if ($request->filled('crop_type')) {
            $query->where('crop_type', 'like', '%' . $request->crop_type . '%');
        }
        if ($request->filled('region')) {
            $query->where('region', 'like', '%' . $request->region . '%');
        }

        $plans = $query->paginate(10);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('farmer.partials.plans_list', compact('plans'))->render()
            ]);
        }

        return view('farmer.plans', compact('plans'));
    }

    public function apply(Request $request, InsurancePlan $plan, \App\Services\InsuranceService $insuranceService)
    {
        try {
            $insuranceService->applyForPlan(auth()->user(), $plan);
            return back()->with('success', 'Applied for policy successfully. Waiting for Proposer approval.');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Please complete your profile first.') {
                return redirect()->route('profile.edit')->with('error', $e->getMessage());
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function policies()
    {
        $policies = auth()->user()->policies()->with('plan.proposer.proposerProfile', 'farmer.farmerProfile')->latest()->paginate(10);
        return view('farmer.policies', compact('policies'));
    }

    public function downloadPolicyPdf(Policy $policy)
    {
        $user = auth()->user();

        // Ensure the policy belongs to the authenticated farmer
        if ($policy->farmer_id !== $user->id) {
            abort(403, 'You are not authorized to download this policy.');
        }

        if ($policy->status !== 'active') {
            return back()->with('error', 'Policy documents are only available for active policies.');
        }

        $policy->load('plan.proposer.proposerProfile', 'farmer.farmerProfile');

        $pdf = Pdf::loadView('farmer.policies.pdf', compact('policy'))
                  ->setPaper('a4', 'portrait');

        $filename = 'policy-' . str_pad($policy->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function compare(Request $request)
    {
        $request->validate([
            'plan_ids' => 'required|array|min:2|max:4',
            'plan_ids.*' => 'exists:insurance_plans,id',
        ], [
            'plan_ids.required' => 'Please select at least 2 plans to compare.',
            'plan_ids.min' => 'Please select at least 2 plans to compare.',
            'plan_ids.max' => 'You can compare a maximum of 4 plans at once.',
        ]);

        $plans = InsurancePlan::with('proposer')->whereIn('id', $request->plan_ids)->get();

        return view('farmer.plans_compare', compact('plans'));
    }
}
