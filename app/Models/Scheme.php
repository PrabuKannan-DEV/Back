<?php

namespace App\Models;

use App\Models\Enrollment;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Scheme extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function enrollments()
    {
    return $this->hasMany(Enrollment::class);
    }
}
