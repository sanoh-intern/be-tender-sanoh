<?php

namespace App\Trait;

use App\Models\ProjectDetail;
use App\Models\ProjectHeader;

trait ProposalStatus
{
    private function checkStatusProposal(int $userId, int $projectId)
    {
        $checkProjectDetail = ProjectDetail::where('supplier_id', $userId)
            ->where('project_header_id', $projectId)
            ->exists();
        if ($checkProjectDetail == false) {
            return 'Not submitted';
        } elseif ($checkProjectDetail == true) {
            $checkWinner = ProjectHeader::whereHas('userWinner', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('id', $projectId)
                ->whereNotNull('final_review_by')
                ->whereNotNull('final_review_at')
                ->exists();

            switch ($checkWinner) {
                case false:
                    $checkIsAnnounced = ProjectHeader::where('id', $projectId)
                        ->whereNotNull('final_review_by')
                        ->whereNotNull('final_review_at')
                        ->exists();
                    if ($checkIsAnnounced == false) {
                        return 'On Review';
                    } elseif ($checkIsAnnounced == true) {
                        return 'Declined';
                    } else {
                        return null;
                    }
                case true:
                    return 'Accepted';
                default:
                    return null;
            }
        }
    }
}
