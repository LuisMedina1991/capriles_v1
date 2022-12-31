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
            $table->decimal('total',10,2);
            $table->integer('items');
            $table->decimal('cash',10,2);
            $table->decimal('change',10,2);
            $table->string('office',45);
            $table->enum('status',['pagado','pendiente','anulado'])->default('pagado');
            $table->unsignedBigInteger('user_id');  //columna para llave foranea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');  //relacion hacia la tabla usuarios
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
