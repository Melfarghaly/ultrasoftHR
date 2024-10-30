<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

   protected $table='hr_jobs';
    protected $fillable = [
        'business_id',
        'job_title',
        'job_description',
    ];
    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\JobFactory::new();
    }
}
