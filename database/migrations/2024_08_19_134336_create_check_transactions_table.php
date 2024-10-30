<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('check_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_id')->constrained()->onDelete('cascade');
            $table->string('bank')->nullable();
            $table->string('cashbox')->nullable();
            $table->string('account')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('check_transactions', function (Blueprint $table) {
            $table->dropForeign(['check_id']);
        });

        Schema::dropIfExists('check_transactions');
    }
}
