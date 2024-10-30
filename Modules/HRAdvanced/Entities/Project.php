<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $table = 'hr_projects';

    protected $fillable = ['name', 'code', 'description', 'business_id'];

    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\ProjectFactory::new();
    }
}
