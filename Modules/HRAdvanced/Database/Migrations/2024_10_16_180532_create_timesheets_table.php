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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->integer('business_id');

            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date'); // Date of the entry
            $table->time('clock_in')->nullable(); // Clock-in time
            $table->time('clock_out')->nullable(); // Clock-out time
            $table->decimal('total_hours', 5, 2)->default(0); // Total hours worked
            $table->decimal('overtime', 5, 2)->default(0); // Total hours worked
            $table->boolean('send')->nullable()->default(false);
            $table->boolean('approved')->nullable()->default(false);
            $table->boolean('posted')->nullable()->default(false);
            $table->boolean('after_posted')->nullable()->default(false);
            $table->string('open')->nullable()->default(false);
            $table->string('timesheet_status')->nullable()->default('work_day');
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
        Schema::dropIfExists('timesheets');
    }
};
