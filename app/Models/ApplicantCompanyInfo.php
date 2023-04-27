<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyInfo extends Model
{
    use HasFactory;

    public $table = 'applicant_company_information';

    protected $fillable = [
        'applicant_id',
        'company_name',
        'year_establish',
        'tel_no',
        'fax_no',
        'email',
        'business_organization_type',
        'owner_name',
        'region',
        'province',
        'municipality',
        'barangay',
        'address_street',
        'map_id',
        'latitude',
        'longitude',
        'marker_description',
        'application_type',
        'application_date',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

}
