<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    public $table = 'facility';

    protected $fillable = [
        'application_id',
        'facility_name',
        'facility_quantity',
        'status',
        'image_string',
        'review_comment',
        'reviewed_by',
        'is_verified',
        'review_level',
        'is_deleted',
    ];
}
