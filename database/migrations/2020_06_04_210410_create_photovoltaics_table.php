<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotovoltaicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photovoltaics', function (Blueprint $table) {
            $table->id();
            $table->string('serial_nr');
            $table->string('model');
            $table->date('purchase_date');
            $table->boolean('broken')
              ->default(false);
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
        Schema::dropIfExists('photovoltaics');
    }
}
