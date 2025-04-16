<?php

use App\Jobs\DeleteVerificationTokenJob;

Schedule::job(new DeleteVerificationTokenJob())->dailyAt('23:59');
