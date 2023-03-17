<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('file_number',45);
            $table->integer('previus_stock');
            $table->integer('quantity');
            $table->string('from_office',45);
            $table->string('to_office',45);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('transfers');
    }
}
