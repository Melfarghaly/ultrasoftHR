<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\HRAdvanced\Entities\SalaryItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmpSalaryItem extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\EmpSalaryItemFactory::new();
    }
 
        /**
         * Get the user associated with the EmpSalaryItem
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function item()
        {
            return $this->belongsTo(SalaryItem::class, 'salary_item_id');
        }
        public function timesheets()
        {
            return $this->hasMany(Timesheet::class);
        }
   
}
