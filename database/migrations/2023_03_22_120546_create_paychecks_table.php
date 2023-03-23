<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaychecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paychecks', function (Blueprint $table) {
            $table->id();
            $table->string('description',100);
            $table->string('number',15);
            $table->decimal('amount',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('bank_id')->constrained();
            $table->foreignId('customer_id')->constrained();
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
        Schema::dropIfExists('paychecks');
    }
}
