<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentationSubcategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentation_subcategory', function (Blueprint $table) {
            $table->id();
            $table->string('prefix',45)->unique();
            $table->string('additional_info',100)->nullable();
            $table->foreignId('presentation_id')->constrained();
            $table->foreignId('subcategory_id')->constrained();
            $table->foreignId('status_id')->constrained();
            /*$table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('presentation_id');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->foreign('presentation_id')->references('id')->on('presentations');*/
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
        Schema::dropIfExists('presentation_subcategory');
    }
}
