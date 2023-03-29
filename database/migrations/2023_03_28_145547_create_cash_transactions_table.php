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
            $table->text('description',1000);
            $table->decimal('amount',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('detail_id')->nullable()->constrained();
            $table->unsignedBigInteger('cashable_id');
            $table->string('cashable_type',80);
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
