<?php

namespace App\Models;

use App\Models\Applicant;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public $table = 'application';

    protected $fillable = [
        'applicant_id',
        'application_id',
        'company_id',
        'application_type',
        'application_status',
        'application_remarks',
        'document_required',
        'document_on_site',
        'transaction_date_time',
        'is_deleted',
        'classification',
        'status',
        'last_reviewer_assigned',
        'reviewer_assigned',

    ];

    protected $casts = [
        'classification' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'id', 'application_id');
    }

}
