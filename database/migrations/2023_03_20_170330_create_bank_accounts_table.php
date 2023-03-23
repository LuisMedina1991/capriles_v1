<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('number',14)->unique();
            $table->enum('type',['caja de ahorros','cuenta corriente']);
            $table->enum('currency',['bolivianos','dolares']);
            $table->decimal('balance',60,30);
            $table->foreignId('status_id')->constrained();
            $table->foreignId('bank_id')->constrained();
            $table->foreignId('company_id')->constrained();
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
        Schema::dropIfExists('bank_accounts');
    }
}
