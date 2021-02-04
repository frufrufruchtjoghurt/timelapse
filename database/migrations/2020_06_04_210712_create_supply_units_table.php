<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id')
                ->unique();
            $table->unsignedBigInteger('router_id')
                ->unique();
            $table->unsignedBigInteger('ups_id')
                ->unique()
                ->nullable();
            $table->boolean('has_heating');
            $table->boolean('has_cooling');
            $table->unsignedBigInteger('photovoltaic_id')
                ->unique()
                ->nullable();
            $table->string('serial_nr')
                ->nullable();
            $table->string('details')
                ->nullable();
            $table->timestamps();

            $table->foreign('router_id')
                ->references('id')
                ->on('routers');
            $table->foreign('ups_id')
                ->references('id')
                ->on('ups');
            $table->foreign('fixture_id')
                ->references('id')
                ->on('fixtures');
            $table->foreign('photovoltaic_id')
                ->references('id')
                ->on('photovoltaics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_units');
    }
}
