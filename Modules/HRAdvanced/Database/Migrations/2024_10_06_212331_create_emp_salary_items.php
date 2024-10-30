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
        Schema::create('emp_salary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('salary_item_id');

            $table->enum('status', ['open', 'certified','ready']); 
            $table->decimal('amount', 10, 2); 
            $table->datetime('starts_at');
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
        Schema::dropIfExists('');
    }
};
