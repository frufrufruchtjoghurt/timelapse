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
            $table->string('serial_nr');
            $table->string('model');
            $table->string('name');
            $table->string('ssid')
                ->nullable();
            $table->string('psk');
            $table->date('purchase_date');
            $table->integer('times_used')
                ->default(0);
            $table->boolean('broken')
              ->default(false);
            $table->unsignedBigInteger('sim_card_id')
                ->unique()
                ->nullable();
            $table->timestamps();

            $table->foreign('sim_card_id')
                ->references('id')
                ->on('sim_cards');
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
