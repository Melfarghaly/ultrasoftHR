<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timesheet extends Model
{
    use HasFactory;

   
    protected $guarded = [];
    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\TimesheetFactory::new();
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
