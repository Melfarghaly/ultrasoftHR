<?php

namespace Modules\HRAdvanced\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'hr_employees';
    protected $fillable = [
        'employee_number',
        'name_ar',
        'name_en',
        'national_id',
        'entry_number',
        'education_level',
        'nationality',
        'occupation',
        'birthdate',
        'religion',
        'marital_status',
        'phone_number',
        'email',
        'contract_start_gregorian',
        'contract_start_hijri',
        'contract_end_gregorian',
        'contract_end_hijri',
        'vacation_days',
        'contract_duration_months',
        'entitled_to_ticket',
        'social_insurance_registration_gregorian',
        'social_insurance_registration_hijri',
        'employee_excluded_from_insurance',
        'exclusion_date_gregorian',
        'exclusion_date_hijri',
        'exclusion_reason',
        'requires_notification',
        'updated_at',
        'updated_time',
        'created_by',
        'company_insurance_number',
        'employee_insurance_number',
        'work_sponsor',
        'work_office_number',
        'iqama',
        'passport',
        'new_passport',
        'project_id',
        'project_code',
        'business_id'
    ];

    protected static function newFactory()
    {
        return \Modules\HRAdvanced\Database\factories\EmployeeFactory::new();
    }
   
    /**
     * Get all of the comments for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaryItems()
    {
        return $this->hasMany(EmpSalaryItem::class, 'employee_id');
    }
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class,'employee_id');
    }
    /**
     * Get the job associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    // Employee.php
    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'employee_id');
    }
}
