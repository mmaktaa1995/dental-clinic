<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_slider', function (Blueprint $table) {
            $table->integer('SERVICE_SLIDER_ID')->primary();
            $table->string('SERVICE_SLIDER_TITLE', 500);
            $table->string('SERVICE_SLIDER_TEXT', 750);
            $table->string('ACTIVE', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_slider');
    }
}
