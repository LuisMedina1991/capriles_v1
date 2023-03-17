<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('file_number',45);
            $table->enum('income_type',['compra','devolucion']);
            $table->enum('payment_type',['efectivo','deposito','cheque','transferencia']);
            $table->integer('previus_stock');
            $table->integer('quantity');
            $table->decimal('total',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->unsignedBigInteger('office_value_id');
            $table->foreign('office_value_id')->references('id')->on('office_value');
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
        Schema::dropIfExists('incomes');
    }
}
