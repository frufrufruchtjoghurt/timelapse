<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->string('serial_nr');
            $table->string('model');
            $table->string('name');
            $table->date('purchase_date');
            $table->integer('times_used')
                ->default(0);
            $table->boolean('broken')
              ->default(false);
            $table->unsignedBigInteger('supply_unit_id')
                ->nullable();
            $table->timestamps();

            $table->foreign('supply_unit_id')
                ->references('id')
                ->on('supply_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cameras');
    }
}
