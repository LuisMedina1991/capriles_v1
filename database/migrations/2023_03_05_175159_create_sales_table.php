<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('file_number',45);
            $table->integer('quantity');
            $table->decimal('sale_price',60,30);
            $table->decimal('total',60,30);
            $table->decimal('utility',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('costumer_id')->constrained();
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
        Schema::dropIfExists('sales');
    }
}
