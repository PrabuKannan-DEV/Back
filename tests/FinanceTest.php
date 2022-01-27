<?php

use App\Models\Group;
use App\Models\Scheme;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FinanceTest extends TestCase
{
    public function testAnAmountCanBeDeposited()
    {
        $user = User::factory()->create();

        $scheme =Scheme::create(
            [
                'name'=>'1L/1',
                'amount'=>'100000',
                'duration'=>'12',
                'interest'=>'0.05',
            ]
            );

        $this->assertEquals(0,$user->enrollments->count());
        $enroll = $user->deposit($scheme->id);
        $this->assertEquals(1,$user->enrollments()->count());
        $this->assertEquals( $scheme->id, $user->enrollments()->where('id',$enroll->id)->first()->scheme->id);
        $this->assertEquals('Active',$user->enrollments()->where('id',$enroll->id)->first()->status);
        $user->withdraw($enroll->id);
        $this->assertEquals('Inactive',$user->enrollments()->where('id',$enroll->id)->first()->status);
        if($user->fresh()->enrollments->first()->withdrawal_date < $user->fresh()->enrollments->first()->maturity_date){
            $this->assertEquals('0.025',($user->enrollments()->first()->scheme->interest)/2);
        }
    }
}
