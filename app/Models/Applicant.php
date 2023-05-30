<?php

namespace App\Models;

use App\Models\ApplicantCompanyInfo;
use App\Models\Application;
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
        'is_deleted',
        'user_id',
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->applicant_firstname . ' ' .
        $this->applicant_middlename . ' ' .
        $this->applicant_lastname . ' ' .
            ($this->applicant_extensionname == 'NA' ? '' : ($this->applicant_extensionname ?? ''));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicantCompanyInfo()
    {
        return $this->belongsTo(ApplicantCompanyInfo::class);
    }

    public function application()
    {
        return $this->hasMany('App\Models\Application', 'application_id');
        //return $this->hasMany(Application::class);
    }
}
