<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAccountInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_account_id',
        'username',
        'password',
        'status',
        'owner_name ',
        'profile_picture',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

}
