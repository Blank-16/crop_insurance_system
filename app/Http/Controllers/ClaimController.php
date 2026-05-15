<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Policy;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $claims = Claim::whereHas('policy', function($q) use ($user) {
            $q->where('farmer_id', $user->id);
        })->with('policy.plan')->get();

        return view('farmer.claims.index', compact('claims'));
    }

    public function create()
    {
        $user = auth()->user();
        $policies = $user->policies()->where('status', 'active')->get();
        return view('farmer.claims.create', compact('policies'));
    }

    public function store(Request $request, \App\Services\WeatherVerificationService $weatherService)
    {
        $request->validate([
            'policy_id' => 'required|exists:policies,id',
            'damage_type' => 'required|string|in:flood,drought,pest,storm',
            'damage_percentage' => 'required|integer|min:1|max:100',
            'description' => 'required|string',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $policy = Policy::with('plan')->where('id', $request->policy_id)->where('farmer_id', auth()->id())->firstOrFail();

        // Check if policy is active
        if ($policy->status !== 'active') {
            return back()->with('error', 'Action denied: Claims can only be filed against currently active policies.');
        }

        // Check if policy expired
        if ($policy->end_date && now()->startOfDay()->greaterThan($policy->end_date)) {
            return back()->with('error', 'This policy expired on ' . \Carbon\Carbon::parse($policy->end_date)->format('M d, Y') . '.');
        }

        // Check for duplicate claim on same policy
        if (Claim::where('policy_id', $policy->id)->whereNotIn('status', ['Rejected'])->exists()) {
            return back()->with('error', 'A claim already exists for this policy and is currently being processed.');
        }

        $calculatedAmount = ($policy->plan->coverage * $request->damage_percentage) / 100;

        $claim = Claim::create([
            'policy_id' => $policy->id,
            'status' => 'Submitted',
            'damage_type' => $request->damage_type,
            'damage_percentage' => $request->damage_percentage,
            'calculated_amount' => $calculatedAmount,
            'description' => $request->description,
        ]);

        // Smart Verification
        $verificationResult = $weatherService->verifyClaim($claim);
        $claim->update([
            'verification_flag' => $verificationResult['flag'],
            'verification_reason' => $verificationResult['reason'],
        ]);

        \App\Models\ClaimLog::create([
            'claim_id' => $claim->id,
            'status' => 'Submitted',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('claim_documents', 'public');
            $claim->documents()->create(['file_path' => $path]);
        }

        return redirect()->route('farmer.claims.index')->with('success', 'Claim submitted successfully. Awaiting review by your provider.');
    }

    public function show(Claim $claim)
    {
        // Ensure farmer owns the claim
        if ($claim->policy->farmer_id !== auth()->id()) {
            abort(403);
        }

        $claim->load(['policy.plan', 'documents', 'logs' => function($q) {
            $q->orderBy('created_at', 'asc');
        }]);

        return view('farmer.claims.show', compact('claim'));
    }
}
