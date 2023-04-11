<?php

namespace App\Models;

use App\Models\ApplicantAccountInfo;
use App\Models\ApplicantCompanyInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'designation',
        'company_info_id',
        'account_info_id',
        'map_id',
        'latitude',
        'longitude',
        'marker_description',
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
