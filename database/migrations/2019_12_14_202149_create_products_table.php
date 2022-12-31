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
            //$table->string('description',100);
            $table->string('code',100)->unique();
            $table->string('brand',45)->nullable();
            $table->string('ring',45)->nullable();
            $table->string('threshing',45)->nullable();
            $table->string('tarp',45)->nullable();
            $table->string('comment',100)->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('subcategory_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('prefix_id')->constrained();
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
