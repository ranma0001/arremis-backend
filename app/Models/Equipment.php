<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    public $table = 'equipment';

    protected $fillable = [
        'application_id',
        'equipment_name',
        'equipment_quantity',
        'review_comment',
        'reviewed_by',
        'status',
        'review_level',
        'is_deleted'];
}
