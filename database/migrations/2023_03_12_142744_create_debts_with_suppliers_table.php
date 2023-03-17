<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsWithSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts_with_suppliers', function (Blueprint $table) {
            $table->id();
            $table->text('description',1000);
            $table->decimal('amount',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('income_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
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
        Schema::dropIfExists('debts_with_suppliers');
    }
}
