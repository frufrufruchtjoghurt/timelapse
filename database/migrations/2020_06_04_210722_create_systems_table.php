<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->unsignedBigInteger('cam_id');
            $table->unsignedBigInteger('router_id');
            $table->unsignedBigInteger('ups_id');
            $table->unsignedBigInteger('fixture_id');
            $table->unsignedBigInteger('photovoltaic_id')
              ->nullable();
            $table->string('vpn_ip')
              ->nullable();
            $table->string('longitude')
              ->nullable();
            $table->string('latitude')
              ->nullable();
            $table->timestamps();

            $table->foreign('cam_id')
              ->references('id')
              ->on('cameras');
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

            $table->primary(['cam_id', 'router_id', 'ups_id', 'fixture_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('systems');
    }
}
