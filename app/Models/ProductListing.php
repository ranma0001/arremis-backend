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
        'manufacturer',
        'fabricator',
        'assembler',
        'distributor',
        'dealer',
        'exporter',
        'cc_no',
        'country_manufacturer',
        'image_string',
        'inspected',
        'review_comment',
        'reviewed_by',
        'is_verified',
        'review_level',
        'is_deleted',
    ];

}
