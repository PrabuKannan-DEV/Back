<?php

namespace App\Models;

use App\Models\Enrollment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function enrollment()
    {
    return $this->belongsTo(Enrollment::class);
    }
}
