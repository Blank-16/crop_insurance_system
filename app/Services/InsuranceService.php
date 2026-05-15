<?php

namespace App\Services;

use App\Models\InsurancePlan;
use App\Models\Policy;
use App\Models\User;
use Exception;

class InsuranceService
{
    /**
     * Apply for an insurance plan with eligibility validation.
     *
     * @param User $farmer
     * @param InsurancePlan $plan
     * @return Policy
     * @throws Exception
     */
    public function applyForPlan(User $farmer, InsurancePlan $plan): Policy
    {
        if (!$farmer->farmerProfile) {
            throw new Exception('Please complete your profile first.');
        }

        $profile = $farmer->farmerProfile;

        // Eligibility Check
        if (strtolower(trim($profile->region)) !== strtolower(trim($plan->region)) || 
            strtolower(trim($profile->crop_type)) !== strtolower(trim($plan->crop_type))) {
            throw new Exception('You are not eligible for this plan. Your region and main crop must exactly match the plan.');
        }

        // Check if a pending or active policy already exists for this plan
        $existingPolicy = Policy::where('farmer_id', $farmer->id)
            ->where('plan_id', $plan->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();
            
        if ($existingPolicy) {
            throw new Exception('You already have an active or pending application for this plan.');
        }

        return Policy::create([
            'farmer_id' => $farmer->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);
    }
}
