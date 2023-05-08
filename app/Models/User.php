<?php

namespace App\Models;

use App\Models\Applicant;
use App\Models\ApplicantCompanyInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'extensionname',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'user_type',
        'status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    public function applicantCompanyInfo()
    {
        return $this->hasOneThrough(ApplicantCompanyInfo::class, Applicant::class);
    }

}
