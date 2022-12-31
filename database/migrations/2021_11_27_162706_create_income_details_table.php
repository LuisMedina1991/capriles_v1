<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost',10,2);
            $table->integer('quantity');
            $table->string('office',45);
            $table->unsignedBigInteger('product_id');  //columna que servira como llave foranea
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade'); //restriccion de llave foranea
            $table->unsignedBigInteger('income_id');  //columna que servira como llave foranea
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade')->onUpdate('cascade'); //restriccion de llave foranea
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
        Schema::dropIfExists('income_details');
    }
}
