<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\ClaimLog;
use App\Models\AuditLog;
use App\Models\Notification;
use Exception;

class ClaimService
{
    /**
     * Process a status update for a claim with business rules validation.
     *
     * @param Claim $claim
     * @param string $newStatus
     * @param string|null $remarks
     * @param int $userId
     * @return Claim
     * @throws Exception
     */
    public function updateStatus(Claim $claim, string $newStatus, ?string $remarks, int $userId): Claim
    {
        $currentStatus = $claim->status;

        // Require remarks when rejecting
        if ($newStatus === 'Rejected' && empty(trim($remarks ?? ''))) {
            throw new Exception('Please provide a reason for rejection.');
        }

        // Prevent invalid status transitions
        if ($currentStatus === 'Submitted' && in_array($newStatus, ['Approved', 'Rejected'])) {
            throw new Exception('Invalid transition: Claim must be reviewed before final approval/rejection.');
        }

        if (in_array($currentStatus, ['Approved', 'Rejected'])) {
            throw new Exception('Action denied: Cannot modify a claim that has already reached a final decision.');
        }

        // Update the claim
        $claim->update([
            'status' => $newStatus,
            'remarks' => $remarks,
        ]);

        // Claim Timeline (Log)
        ClaimLog::create([
            'claim_id' => $claim->id,
            'status' => $newStatus,
        ]);

        // Audit Log & Notification for terminal states
        if (in_array($newStatus, ['Approved', 'Rejected'])) {
            AuditLog::create([
                'user_id' => $userId,
                'action' => "{$newStatus} Claim",
                'target_id' => $claim->id,
            ]);

            Notification::create([
                'user_id' => $claim->policy->farmer_id,
                'message' => "Your claim #{$claim->id} has been {$newStatus}. " . ($remarks ? "Reason: {$remarks}" : ''),
            ]);
        }

        return $claim;
    }
}
