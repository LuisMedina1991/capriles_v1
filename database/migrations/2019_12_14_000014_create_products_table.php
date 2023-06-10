<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('code',45)->unique();
            $table->string('comment',45)->nullable();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->unsignedBigInteger('presentation_subcategory_id');
            $table->foreign('presentation_subcategory_id')->references('id')->on('presentation_subcategory');
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
        Schema::dropIfExists('products');
    }
}
