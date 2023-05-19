<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    use HasFactory;

    public $table = 'service_center';

    protected $fillable = [
        'application_id',
        'center_name',
        'contact',
        'email_address',
        'address',
        'longitude',
        'latitude',
        'review_comment',
        'reviewed_by',
        'status',
        'review_level',
        'is_deleted',
    ];
}
