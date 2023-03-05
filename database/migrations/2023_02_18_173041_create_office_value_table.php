<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_value', function (Blueprint $table) {
            $table->id();
            $table->integer('stock')->nullable();
            $table->integer('alerts')->nullable();
            $table->foreignId('office_id')->constrained();
            $table->foreignId('value_id')->constrained();
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
        Schema::dropIfExists('office_value');
    }
}
