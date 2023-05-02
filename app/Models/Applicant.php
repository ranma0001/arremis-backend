<?php

namespace App\Models;

use App\Models\ApplicantAccountInfo;
use App\Models\ApplicantCompanyInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    public $table = 'applicants';

    protected $fillable = [
        'applicant_firstname',
        'applicant_middlename',
        'applicant_lastname',
        'applicant_extensionname',
        'designation',
        'is_deleted',
    ];

    public function accountinfo()
    {
        return $this->hasOne(ApplicantAccountInfo::class);
    }

    public function companyinfo()
    {
        return $this->hasOne(ApplicantCompanyInfo::class);
    }
}
