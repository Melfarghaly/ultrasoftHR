<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_name','item_type','description'];
    
    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\SalaryItemFactory::new();
    }
}
