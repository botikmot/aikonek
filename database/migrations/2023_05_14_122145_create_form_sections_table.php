<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_page_id');
            $table->string('title');
            $table->timestamps();

            $table->foreign('form_page_id')->references('id')->on('form_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_sections');
    }
}
