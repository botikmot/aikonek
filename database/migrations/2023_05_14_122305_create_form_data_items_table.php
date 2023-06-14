<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormDataItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_data_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_section_id');
            $table->string('label')->nullable();
            $table->string('type');
            $table->integer('position');
            $table->text('properties')->nullable();
            $table->timestamps();

            $table->foreign('form_section_id')->references('id')->on('form_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data_items');
    }
}
