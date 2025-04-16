<?php

namespace App\Jobs;

use App\Models\PasswordResetTokens;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteVerificationTokenJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = Carbon::now();
        $tommorow = Carbon::now()->subDays(2);

        PasswordResetTokens::whereBetween('created_at', [$today,$tommorow])->delete();
    }
}
