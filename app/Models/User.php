<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Scheme;
use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\Transaction;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
   
    public function deposit($scheme_id)
    {
        $scheme = Scheme::find($scheme_id);
        $enrollment =$this->enrollments()->create(
            ['scheme_id'=>$scheme_id,
             'deposit_date'=>Carbon::now(),
            'maturity_date'=>Carbon::now()->addMonths($scheme->duration),
    ]);
        $transaction = Transaction::create([
            'enrollment_id'=>$enrollment->id,
            'amount'=>$scheme->amount,
            'transaction_type'=>'deposit',
        ]);
        return $enrollment;
    }
    public function withdraw($enroll_id)
    {
    $enrollment =$this->enrollments()->find($enroll_id);
    if($enrollment->status!='Active'){
        return 'Failed';
    }
    if(\Carbon\Carbon::now()==$enrollment->maturity_date)
    $total = $enrollment->scheme->amount +
     (($enrollment->scheme->amount*($enrollment->scheme->interest/12)*Carbon::parse($enrollment->deposit_date)->diffInMonths()));
    else
    $total = $enrollment->scheme->amount +
    (($enrollment->scheme->amount*($enrollment->scheme->interest/24)*Carbon::parse($enrollment->deposit_date)->diffInMonths()))-500;

    $transaction = Transaction::create([
        'enrollment_id'=>$enrollment->id,
        'amount'=>$total,
        'transaction_type'=>'withdraw',
    ]);
    $this->enrollments()->find($enroll_id)->update([
        'status'=>'Inactive',
    'withdrawal_date'=>Carbon::now(),
    ]);
    return('Success');
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
