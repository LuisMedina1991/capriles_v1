<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceSheetAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_sheet_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->unique();
            $table->string('alias',45)->unique();
            $table->decimal('balance',60,30);
            $table->foreignId('subtype_id')->constrained();
            $table->foreignId('status_id')->constrained();
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
        Schema::dropIfExists('balance_sheet_accounts');
    }
}
