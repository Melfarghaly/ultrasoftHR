<?php

namespace Modules\Repair\Entities;

use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'checklist' => 'array',
    ];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'repair_guarantee';

    /**
     * Return the customer for the project.
     */
    public function customer()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Contact', 'supplier_id');
    }
    /**
     * user added job sheet.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * technecian for job sheet.
     */
    public function technician()
    {
        return $this->belongsTo('App\User', 'service_staff');
    }

    /**
     * status of job sheet.
     */
    public function status()
    {
        return $this->belongsTo('Modules\Repair\Entities\RepairStatus', 'status_id');
    }

    /**
     * get device for job sheet
     */
    

}
