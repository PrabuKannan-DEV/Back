<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use App\Models\Scheme;
use App\Models\Enrollment;
use App\Models\Transaction;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Enrollment extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $with = ['scheme'];

    public function user()
    {
       return $this->belongsTo(User::class);
    }
    public function scheme()
    {
         return $this->belongsTo(Scheme::class);
    }
    public function transactions()
    {
    return $this->hasMany(Transaction::class);
    }
  }
