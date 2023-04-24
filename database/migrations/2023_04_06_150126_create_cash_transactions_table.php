<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('file_number',45);
            $table->enum('action',['ingreso','egreso']);
            $table->string('type',45)->nullable();
            $table->integer('relation')->nullable();
            $table->string('description',255);
            $table->decimal('amount',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('balance_sheet_account_id')->constrained();
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
        Schema::dropIfExists('cash_transactions');
    }
}
