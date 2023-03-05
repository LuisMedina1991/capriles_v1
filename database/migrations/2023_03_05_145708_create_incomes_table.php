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
            $table->enum('type',['compra','devolucion']);
            $table->decimal('total',60,30);
            $table->integer('quantity');
            $table->string('file_number',45);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->foreignId('costumer_id')->nullable()->constrained();
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
