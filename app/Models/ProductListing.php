<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductListing extends Model
{
    use HasFactory;

    public $table = 'product_listing';

    protected $fillable = [
        'application_id',
        'item_name',
        'item_brand',
        'description',
        'classification',
        'cc_no',
        'country_manufacturer',
        'inspected',
        'review_comment',
        'reviewed_by',
        'status',
        'review_level',
        'file_location',
        'file_name',
        'file_type',
        'is_deleted',
    ];

    protected $casts = [
        'classification' => 'array',
    ];

}
