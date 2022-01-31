<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Enrollment;
use App\Jobs\UpdateInterestForEnrollmentJob;

class UpdateInterestForAllEnrollmentsJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $enrollments = Enrollment::where('maturity_date','>', Carbon::now())->get();
        foreach($enrollments as $enrollment){
            dispatch(new UpdateInterestForEnrollmentJob($enrollment));
        }
    }
}
