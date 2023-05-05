<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    use HasFactory;

    public $table = 'service_centers';

    protected $fillable = [
        'applicant_id',
        'center_name',
        'contact',
        'email',
        'address',
        'longitude',
        'latitude',
        'review_comment',
        'reviewed_by',
        'is_verified',
        'review_level',
        'is_deleted',
    ];
}
