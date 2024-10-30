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
        Schema::create('salary_items', function (Blueprint $table) {
            $table->id();
            
            $table->string('item_name'); 
            $table->string('item_code');
            $table->enum('item_type', ['earning', 'deduction']); 
            //$table->enum('status', ['open', 'certified','ready']); 
         //   $table->decimal('amount', 10, 2); 
            //$table->boolean('is_taxable')->default(0); 
           // $table->boolean('is_fixed')->default(0); 
            $table->text('description')->nullable(); 
           
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
