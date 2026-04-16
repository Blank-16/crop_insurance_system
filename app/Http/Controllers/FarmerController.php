<?php

namespace App\Http\Controllers;

use App\Models\InsurancePlan;
use App\Models\Policy;
use App\Models\FarmerProfile;
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
        ]);

        auth()->user()->farmerProfile()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only(['land_size', 'crop_type', 'region'])
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

    public function apply(Request $request, InsurancePlan $plan)
    {
        $user = auth()->user();
        if (!$user->farmerProfile) {
            return redirect()->route('profile.edit')->with('error', 'Please complete your profile first.');
        }

        $profile = $user->farmerProfile;

        // Eligibility Check
        if (strtolower(trim($profile->region)) !== strtolower(trim($plan->region)) || 
            strtolower(trim($profile->crop_type)) !== strtolower(trim($plan->crop_type))) {
            return back()->with('error', 'You are not eligible for this plan. Your region and main crop must exactly match the plan.');
        }

        Policy::create([
            'farmer_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Applied for policy successfully. Waiting for Proposer approval.');
    }

    public function policies()
    {
        $policies = auth()->user()->policies()->with('plan.proposer')->latest()->paginate(10);
        return view('farmer.policies', compact('policies'));
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
