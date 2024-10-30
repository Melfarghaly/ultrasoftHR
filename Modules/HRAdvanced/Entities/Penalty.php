<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HRAdvanced\Entities\Employee;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'type',
        'hours',
        'penalty_date',
        'description',
        'document',
        'created_by',
    ];
    // Penalty.php
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\PenaltyFactory::new();
    }
}
