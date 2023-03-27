<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->enum('action',['ingreso','egreso']);
            $table->string('relation_file_number',45);
            $table->text('description',1000);
            $table->decimal('amount',60,30);
            $table->decimal('previus_balance',60,30);
            $table->decimal('actual_balance',60,30);
            $table->foreignId('status_id')->constrained();
            $table->unsignedBigInteger('detailable_id');
            $table->string('detailable_type',80);
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
        Schema::dropIfExists('details');
    }
}
