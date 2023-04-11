<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_info_id',
        'company_name',
        'year_establish',
        'location_id',
        'tel_no',
        'fax_no',
        'email',
        'business_organization',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

}
