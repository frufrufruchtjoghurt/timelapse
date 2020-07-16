<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sim_id');
            $table->integer('serial_nr');
            $table->string('model');
            $table->year('build_year');
            $table->boolean('broken')
              ->default(false);
            $table->timestamps();

            $table->foreign('sim_id')
              ->references('id')
              ->on('sim_cards')
              ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routers');
    }
}
