<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Scheme;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinanceTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme->id]);
        $enroll=json_decode($this->response->getContent());
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme->id]);
        $enroll=json_decode($this->response->getContent());
        $this->call('get','/enrollments/'.$enroll->id.'/withdraw', ['user_id'=>$user->id]);
        $this->assertEquals('Inactive', $user->fresh()->enrollments()->where('id', $enroll->id)->first()->status);
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme->id]);
        $enroll=json_decode($this->response->getContent());
        $this->call('get','/enrollments/'.$enroll->id.'/withdraw', ['user_id'=>$user->id]);
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme1->id]);
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme2->id]);
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme3->id]);
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme->id]);
        $enroll=json_decode($this->response->getContent());
        Carbon::setTestNow(Carbon::parse('02/02/2023'));
        $this->call('get','/enrollments/'.$enroll->id.'/withdraw', ['user_id'=>$user->id]);
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
        $this->call('get','/deposits', ['user_id'=>$user->id, 'scheme_id'=>$scheme->id]);
        $enroll=json_decode($this->response->getContent());
        $result=$this->call('get','/enrollments/'.$enroll->id.'/withdraw', ['user_id'=>$user->id]);
        $result= json_decode($this->response->getContent());
        $this->assertEquals('Success', $result);
        $result=$this->call('get','/enrollments/'.$enroll->id.'/withdraw',['user_id'=>$user->id]);
        $result= json_decode($this->response->getContent());
        $this->assertEquals('Failed', $result);
    }
}
