<?php

namespace App\Http\Controllers;

use App\Models\InsurancePlan;
use Illuminate\Http\Request;

class InsurancePlanController extends Controller
{
    public function index()
    {
        $plans = auth()->user()->insurancePlans()->paginate(10);
        return view('proposer.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('proposer.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_type' => 'required|string',
            'region' => 'required|string',
            'premium' => 'required|numeric',
            'coverage' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        auth()->user()->insurancePlans()->create($validated);

        return redirect()->route('proposer.plans.index')->with('success', 'Plan created successfully!');
    }

    public function edit(InsurancePlan $plan)
    {
        return view('proposer.plans.edit', compact('plan'));
    }

    public function update(Request $request, InsurancePlan $plan)
    {
        $validated = $request->validate([
            'crop_type' => 'required|string',
            'region' => 'required|string',
            'premium' => 'required|numeric',
            'coverage' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $plan->update($validated);

        return redirect()->route('proposer.plans.index')->with('success', 'Plan updated successfully!');
    }

    public function destroy(InsurancePlan $plan)
    {
        $plan->delete();
        return redirect()->route('proposer.plans.index')->with('success', 'Plan deleted.');
    }
}
