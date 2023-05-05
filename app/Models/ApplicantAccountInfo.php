<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantAccountInfo extends Model
{
    use HasFactory;

    public $table = 'applicant_account_informations';

    protected $fillable = [
        'applicant_id',
        'username',
        'password',
        'status',
        'profile_picture',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

}
