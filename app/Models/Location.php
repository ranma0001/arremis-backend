<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $table = 'locations';

    protected $fillable = [
        'id',
        'arr',
        'reg_abbreviation',
        'reg',
        'region',
        'province',
        'district',
        'municipality',
        'barangay',
        'psgc_code',
    ];
}
