<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Policy;
use App\Models\Claim;
use App\Models\InsurancePlan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $policiesCount = Policy::count();
        $claimsCount = Claim::count();
        $plansCount = InsurancePlan::count();

        $recentClaims = Claim::with(['policy.farmer', 'policy.plan'])->latest()->take(5)->get();

        // Advanced Analytics
        $approvedCount = Claim::where('status', 'Approved')->count();
        $rejectedCount = Claim::where('status', 'Rejected')->count();
        $totalResolved = $approvedCount + $rejectedCount;
        
        $approvedPercentage = $totalResolved > 0 ? round(($approvedCount / $totalResolved) * 100) : 0;
        $rejectedPercentage = $totalResolved > 0 ? round(($rejectedCount / $totalResolved) * 100) : 0;

        // Claim Resolution Chart Data
        $claimsByStatus = Claim::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();
        
        $chartData = [
            'status_labels' => array_keys($claimsByStatus),
            'status_series' => array_values($claimsByStatus),
        ];

        // Crop Distribution Data
        $cropDistribution = Policy::join('insurance_plans', 'policies.plan_id', '=', 'insurance_plans.id')
            ->select('insurance_plans.crop_type', \DB::raw('count(*) as total'))
            ->groupBy('insurance_plans.crop_type')
            ->pluck('total', 'insurance_plans.crop_type')->toArray();

        $chartData['crop_labels'] = array_keys($cropDistribution);
        $chartData['crop_series'] = array_values($cropDistribution);

        $mostClaimedCrop = Claim::join('policies', 'claims.policy_id', '=', 'policies.id')
            ->join('insurance_plans', 'policies.plan_id', '=', 'insurance_plans.id')
            ->select('insurance_plans.crop_type', \DB::raw('count(claims.id) as total_claims'))
            ->groupBy('insurance_plans.crop_type')
            ->orderByDesc('total_claims')
            ->first();

        return view('admin.dashboard', compact('usersCount', 'policiesCount', 'claimsCount', 'plansCount', 'recentClaims', 'approvedPercentage', 'rejectedPercentage', 'mostClaimedCrop', 'chartData'));
    }

    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }
}
