<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentaryRequirement extends Model
{
    use HasFactory;

    public $table = 'documentary_requirement';

    protected $fillable = [
        'application_id',
        'document_name',
        'document_type',
        'file_location',
        'review_comment',
        'review_by',
        'document_status',
        'review_level',
        'is_deleted',
    ];
}
