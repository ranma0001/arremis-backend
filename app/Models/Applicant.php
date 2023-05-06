<?php

namespace App\Models;

use App\Models\ApplicantCompanyInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    public $table = 'applicant';

    protected $fillable = [
        'applicant_firstname',
        'applicant_middlename',
        'applicant_lastname',
        'applicant_extensionname',
        'designation',
        'profile_picture',
        'is_deleted',
        'user_id',
    ];

    public function companyinfo()
    {
        return $this->hasOne(ApplicantCompanyInfo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
