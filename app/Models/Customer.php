<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Group;
use App\Models\Enrollment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function deposit($scheme_id)
    {   
        $scheme = Scheme::find($scheme_id);
        $enrollment =$this->enrollments()->create(
            ['scheme_id'=>$scheme_id,
             'deposit_date'=>Carbon::now(),
            'maturity_date'=>Carbon::now()->addMonths($scheme->duration),
    ]);
        return $enrollment;
    }
    public function  withdraw($scheme_id)
    {   
        return $this->enrollments()->where('scheme_id', $scheme_id)->update(['status'=>'inactive']);
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function user()
    {
     return $this->belongsTo(User::class);       
    }
}
