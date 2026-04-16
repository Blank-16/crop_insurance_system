<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\InsurancePlan;
use App\Models\Policy;
use App\Models\Claim;
use App\Models\ClaimLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users Setup
        $admin = User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $proposer = User::firstOrCreate(['email' => 'proposer@admin.com'], [
            'name' => 'AgriProtect Proposer',
            'password' => bcrypt('password'),
            'role' => 'proposer',
        ]);

        $proposer2 = User::firstOrCreate(['email' => 'proposer2@admin.com'], [
            'name' => 'GreenShield Insurance',
            'password' => bcrypt('password'),
            'role' => 'proposer',
        ]);

        $farmer = User::firstOrCreate(['email' => 'farmer@admin.com'], [
            'name' => 'John Doe Farmer',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);
        
        $farmer2 = User::firstOrCreate(['email' => 'farmer2@admin.com'], [
            'name' => 'Jane Smith Farmer',
            'password' => bcrypt('password'),
            'role' => 'farmer',
        ]);

        // Profiles
        if (!$farmer->farmerProfile) {
            $farmer->farmerProfile()->create(['land_size' => '10 Acres', 'crop_type' => 'Wheat', 'region' => 'North']);
        }
        if (!$farmer2->farmerProfile) {
            $farmer2->farmerProfile()->create(['land_size' => '25 Acres', 'crop_type' => 'Corn', 'region' => 'South']);
        }

        if (!$proposer->proposerProfile) {
            $proposer->proposerProfile()->create(['company_name' => 'AgriProtect Insurance Co.']);
        }
        if (!$proposer2->proposerProfile) {
            $proposer2->proposerProfile()->create(['company_name' => 'GreenShield Insurance Ltd.']);
        }

        // 2. Data Density: Plans (5-10 plans)
        $plans = [
            ['proposer_id' => $proposer->id, 'crop_type' => 'Wheat', 'region' => 'North', 'premium' => 500.00, 'coverage' => 10000.00, 'duration' => 12],
            ['proposer_id' => $proposer->id, 'crop_type' => 'Corn', 'region' => 'South', 'premium' => 800.00, 'coverage' => 15000.00, 'duration' => 12],
            ['proposer_id' => $proposer->id, 'crop_type' => 'Rice', 'region' => 'East', 'premium' => 600.00, 'coverage' => 12000.00, 'duration' => 6],
            ['proposer_id' => $proposer2->id, 'crop_type' => 'Wheat', 'region' => 'North', 'premium' => 450.00, 'coverage' => 9500.00, 'duration' => 10],
            ['proposer_id' => $proposer2->id, 'crop_type' => 'Soybean', 'region' => 'Midwest', 'premium' => 700.00, 'coverage' => 18000.00, 'duration' => 12],
            ['proposer_id' => $proposer2->id, 'crop_type' => 'Cotton', 'region' => 'South', 'premium' => 900.00, 'coverage' => 22000.00, 'duration' => 8],
        ];

        $createdPlans = [];
        foreach ($plans as $planData) {
            $createdPlans[] = InsurancePlan::firstOrCreate([
                'crop_type' => $planData['crop_type'],
                'region' => $planData['region'],
                'proposer_id' => $planData['proposer_id'],
                'premium' => $planData['premium']
            ], $planData);
        }

        // 3. Data Density: Policies (mix of active, expired, pending)
        $policiesData = [
            ['farmer_id' => $farmer->id, 'plan_id' => $createdPlans[0]->id, 'status' => 'active', 'start_date' => Carbon::now()->subMonths(3), 'end_date' => Carbon::now()->addMonths(9)],
            ['farmer_id' => $farmer->id, 'plan_id' => $createdPlans[3]->id, 'status' => 'active', 'start_date' => Carbon::now()->subMonths(1), 'end_date' => Carbon::now()->addMonths(9)],
            ['farmer_id' => $farmer->id, 'plan_id' => $createdPlans[0]->id, 'status' => 'rejected', 'start_date' => null, 'end_date' => null],
            ['farmer_id' => $farmer->id, 'plan_id' => $createdPlans[3]->id, 'status' => 'expired', 'start_date' => Carbon::now()->subMonths(13), 'end_date' => Carbon::now()->subMonths(1)], // Expired policy
            ['farmer_id' => $farmer2->id, 'plan_id' => $createdPlans[1]->id, 'status' => 'active', 'start_date' => Carbon::now()->subMonths(5), 'end_date' => Carbon::now()->addMonths(7)],
            ['farmer_id' => $farmer2->id, 'plan_id' => $createdPlans[5]->id, 'status' => 'pending', 'start_date' => null, 'end_date' => null],
            ['farmer_id' => $farmer2->id, 'plan_id' => $createdPlans[1]->id, 'status' => 'expired', 'start_date' => Carbon::now()->subMonths(24), 'end_date' => Carbon::now()->subMonths(12)],
        ];

        $createdPolicies = [];
        foreach ($policiesData as $pData) {
            $createdPolicies[] = Policy::create($pData);
        }

        // 4. Data Density: Claims (mix of statuses, realistic damage)
        $claimsData = [
            // Farmer 1 Claims
            ['policy_id' => $createdPolicies[0]->id, 'status' => 'Submitted', 'damage_type' => 'drought', 'damage_percentage' => 40, 'calculated_amount' => 4000, 'description' => 'Severe heatwave damaged a portion of the wheat crop.', 'created_at' => Carbon::now()->subDays(2)],
            ['policy_id' => $createdPolicies[1]->id, 'status' => 'Under Review', 'damage_type' => 'flood', 'damage_percentage' => 60, 'calculated_amount' => 5700, 'description' => 'Heavy unseasonal rains flooded fields.', 'created_at' => Carbon::now()->subDays(10)],
            ['policy_id' => $createdPolicies[3]->id, 'status' => 'Rejected', 'damage_type' => 'pest', 'damage_percentage' => 15, 'calculated_amount' => 1425, 'description' => 'Minor pest infection.', 'remarks' => 'Damage percentage too low to qualify under plan terms.', 'created_at' => Carbon::now()->subMonths(2)],
            
            // Farmer 2 Claims
            ['policy_id' => $createdPolicies[4]->id, 'status' => 'Approved', 'damage_type' => 'storm', 'damage_percentage' => 85, 'calculated_amount' => 12750, 'description' => 'Hailstorm completely destroyed the corn field.', 'created_at' => Carbon::now()->subDays(45)],
            ['policy_id' => $createdPolicies[6]->id, 'status' => 'field_verification', 'damage_type' => 'flood', 'damage_percentage' => 50, 'calculated_amount' => 7500, 'description' => 'River overflowed affecting corn yields.', 'created_at' => Carbon::now()->subDays(5)],
        ];

        foreach ($claimsData as $cData) {
            $creationDate = $cData['created_at'];
            $claim = Claim::create([
                'policy_id' => $cData['policy_id'],
                'status' => $cData['status'],
                'damage_type' => $cData['damage_type'],
                'damage_percentage' => $cData['damage_percentage'],
                'calculated_amount' => $cData['calculated_amount'],
                'description' => $cData['description'],
                'remarks' => $cData['remarks'] ?? null,
                'created_at' => $creationDate,
                'updated_at' => $creationDate
            ]);

            // Add realistic logs based on status
            ClaimLog::create(['claim_id' => $claim->id, 'status' => 'Submitted', 'created_at' => $creationDate, 'updated_at' => $creationDate]);
            
            if (in_array($claim->status, ['Under Review', 'field_verification', 'Approved', 'Rejected'])) {
                ClaimLog::create(['claim_id' => $claim->id, 'status' => 'Under Review', 'created_at' => $creationDate->copy()->addDays(1), 'updated_at' => $creationDate->copy()->addDays(1)]);
            }
            if (in_array($claim->status, ['field_verification', 'Approved'])) {
                ClaimLog::create(['claim_id' => $claim->id, 'status' => 'field_verification', 'created_at' => $creationDate->copy()->addDays(3), 'updated_at' => $creationDate->copy()->addDays(3)]);
            }
            if (in_array($claim->status, ['Approved', 'Rejected'])) {
                ClaimLog::create(['claim_id' => $claim->id, 'status' => $claim->status, 'created_at' => $creationDate->copy()->addDays(7), 'updated_at' => $creationDate->copy()->addDays(7)]);
            }
        }
    }
}
