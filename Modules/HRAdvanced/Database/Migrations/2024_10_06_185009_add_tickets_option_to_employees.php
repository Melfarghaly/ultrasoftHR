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
        Schema::table('hr_employees', function (Blueprint $table) {
        
                // Adding fields related to entitled to ticket and ticket type
               // $table->boolean('entitled_to_ticket')->default(0); // 0 = No, 1 = Yes
                $table->string('ticket_type')->nullable(); // 'cash' or 'physical'
                
                // If ticket type is 'cash', we will store the cash value
                $table->decimal('cash_value', 10, 2)->nullable(); // Cash ticket value
                
                // If ticket type is 'physical', we will store the trip details
                $table->string('ticket_from')->nullable(); // City of departure
                $table->string('ticket_to')->nullable(); // Destination city
                $table->string('trip_type')->nullable(); // 'one_way' or 'round_trip'

                $table->boolean('entitled_to_medical_insurance')->default(0); // 0: No, 1: Yes

                // Add column for type of medical insurance (only applicable if entitled_to_medical_insurance is 1)
                $table->enum('medical_insurance_type', ['individual', 'family'])->nullable(); // Type of medical insurance
    
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
