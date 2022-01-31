<?php

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Scheme;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FinanceTest extends TestCase
{
    //test a customer can deposit amount
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
        $this->assertEquals(0, $user->enrollments->count());
        $enroll = $user->deposit($scheme->id);
        $this->assertEquals(1, $user->enrollments()->count());
        $this->assertEquals($scheme->id, $user->enrollments()->where('id', $enroll->id)->first()->scheme->id);
        $this->assertEquals('Active', $user->enrollments()->where('id', $enroll->id)->first()->status);
    }
    //test a customer can withdraw amount
    public function testAnAmountCanBeWithdrawn()
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
        $enroll = $user->deposit($scheme->id);
        $user->withdraw($enroll->id);
        $this->assertEquals('Inactive', $user->enrollments()->where('id', $enroll->id)->first()->status);
    }

    //test a customer gets only half the intended interest if withdrawn before maturity period
    public function testIfInterestIsHalfTheIntendedInterestWhenWithdrawnBeforeMaturity()
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
        $enroll = $user->deposit($scheme->id);
        $user->withdraw($enroll->id);
        $this->assertEquals('0.025',($user->enrollments()->first()->scheme->interest)/2);
    }
    //test a customer can see his enrollments
    public function testACustomerCanSeeHisEnrollments()
    {
        $user = User::factory()->create();
        
        $scheme1 =Scheme::create(
            [
                'name'=>'2L/24',
                'amount'=>'200000',
                'duration'=>'24',
                'interest'=>'0.08',
            ]
        );
        $scheme2 =Scheme::create(
            [
                'name'=>'3L/36',
                'amount'=>'300000',
                'duration'=>'36',
                'interest'=>'0.10',
            ]
        );
        $scheme3 =Scheme::create(
            [
                'name'=>'1L/24',
                'amount'=>'100000',
                'duration'=>'24',
                'interest'=>'0.05',
            ]
        );
        $enroll = $user->deposit($scheme1->id);
        $enroll = $user->deposit($scheme2->id);
        $enroll = $user->deposit($scheme3->id);
        $this->assertEquals(3, $user->enrollments()->count());
    }

    //test a customer gets intended interest while withdrawing after maturity date
    public function testIfACustomerGetsIntentedInterestWhilwWithdrawingAfterMaturityDate()
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
        $enroll = $user->deposit($scheme->id);
        Carbon::setTestNow(Carbon::parse('02/02/2023'));
        $user->withdraw($enroll->id);
        $this->assertEquals('0.05',($user->enrollments()->first()->scheme->interest));
    }
    //test a customer cannot withdraw already withdrawn enrollment
    public function testACustomerCannotWithdrawAlreadyWithdrawnEnrollment()
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
        $enroll = $user->deposit($scheme->id);
        $result = $user->withdraw($enroll->id);
        $this->assertEquals('Success', $result);
        $result = $user->withdraw($enroll->id);
        $this->assertEquals('Failed', $result);
    }
}
