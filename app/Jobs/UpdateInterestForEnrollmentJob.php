<?php

namespace App\Jobs;

use Carbon\Carbon;

class UpdateInterestForEnrollmentJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $enr;
    public function __construct($enrollment)
    {
        $this->enr = $enrollment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Carbon::now()>=$this->enr->maturity_date) {
            $ir = $this->enr->scheme-> interest;
        }
            else{
                $ir = $this->enr->scheme-> interest;
                $ir = $ir/2;
            }
            $ci = round(($ir* $this->enr->scheme->amount)/12*(Carbon::parse($this->enr->deposit_date)->diffInMonths()));
       $this->enr->update(['current_interest'=>$ci]);
    }
}
