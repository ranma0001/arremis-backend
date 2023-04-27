<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    public $table = 'facilities';

    protected $fillable = [
        'applicant_id',
        'id_applicant',
        'facility_name',
        'facility_quantity',
        'status',
        'image_string',
        'review_comment',
        'reviewed_by',
        'is_verified',
        'review_level',
    ];
}
