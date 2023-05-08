<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkDealers extends Model
{
    use HasFactory;

    public $table = 'network_dealer';

    protected $fillable = [
        'applicant_id',
        'company_name',
        'contact',
        'address',
        'review_comment',
        'reviewed_by',
        'is_verified',
        'review_level',
        'is_deleted',
    ];
}
