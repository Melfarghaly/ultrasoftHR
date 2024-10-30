<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
            Schema::create('hr_employees', function (Blueprint $table) {
                $table->id();
                $table->string('employee_number'); // Employee ID
                $table->string('name_ar'); // Arabic Name
                $table->string('name_en'); // English Name
                $table->bigInteger('national_id'); // National ID or Iqama
                $table->bigInteger('entry_number'); // Entry Number
                $table->string('education_level'); // Education Level
                $table->string('nationality'); // Dropdown for nationality
                $table->string('occupation')->nullable(); // Dropdown for occupation
                $table->date('birthdate'); // Date of Birth
                $table->string('religion'); // Religion
                $table->string('marital_status'); // Dropdown for marital status
                $table->string('phone_number'); // Mobile Number with 966
                $table->string('email'); // Email
            
                // Contract Information
                $table->date('contract_start_gregorian'); // Contract Start (Gregorian)
                $table->date('contract_start_hijri'); // Contract Start (Hijri)
                $table->date('contract_end_gregorian'); // Contract End (Gregorian)
                $table->date('contract_end_hijri'); // Contract End (Hijri)
                $table->integer('vacation_days'); // Number of contractual vacation days
                $table->integer('contract_duration_months'); // Contract duration in months
                $table->boolean('entitled_to_ticket')->default(0); // Entitled to travel ticket (Yes/No)
            
                // Social Security Information
                $table->date('social_insurance_registration_gregorian')->nullable(); // Gregorian date of insurance registration
                $table->date('social_insurance_registration_hijri')->nullable(); // Hijri date of insurance registration
                $table->boolean('employee_excluded_from_insurance')->default(0); // Excluded from insurance (Yes/No)
                $table->date('exclusion_date_gregorian')->nullable(); // Exclusion date (Gregorian)
                $table->date('exclusion_date_hijri')->nullable(); // Exclusion date (Hijri)
                $table->string('exclusion_reason')->nullable(); // Dropdown for exclusion reason
            
                // Notifications
                $table->boolean('requires_notification')->default(0); // Requires notification (Yes/No)
                $table->timestamp('last_updated_at')->nullable(); // Last modification date
                $table->time('updated_time')->nullable(); // Last modification time
                $table->string('created_by'); // Created by (Username)
            
                // Social Security Details
                $table->bigInteger('company_insurance_number')->nullable(); // Company insurance subscription number
                $table->bigInteger('employee_insurance_number')->nullable(); // Employee insurance subscription number
                $table->string('work_sponsor')->nullable(); // Dropdown for work sponsor
                $table->bigInteger('work_office_number')->nullable(); // Work office number
            
                // Additional documents
                $table->string('iqama')->nullable(); // Iqama (PDF/Picture)
                $table->string('passport')->nullable(); // Passport (PDF/Picture)
                $table->string('new_passport')->nullable(); // New Passport (PDF/Picture)
            
                // Project
                $table->string('project_id')->nullable(); // Dropdown for project name
            
                $table->timestamps();
            });
            
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
