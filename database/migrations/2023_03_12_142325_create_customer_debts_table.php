<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_debts', function (Blueprint $table) {
            $table->id();
            $table->text('description',1000);
            $table->decimal('amount',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('sale_id')->constrained();
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
        Schema::dropIfExists('customer_debts');
    }
}
