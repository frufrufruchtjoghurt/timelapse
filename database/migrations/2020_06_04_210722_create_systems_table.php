<?php

use App\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Models\Photovoltaic;
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
            $table->id();
            $table->unsignedBigInteger('fixture_id')
              ->unique();
            $table->unsignedBigInteger('router_id')
              ->unique();
            $table->unsignedBigInteger('sim_card_id')
              ->unique();
            $table->unsignedBigInteger('ups_id')
              ->unique();
            $table->unsignedBigInteger('heating_id')
              ->unique()
              ->nullable();
            $table->unsignedBigInteger('photovoltaic_id')
              ->unique()
              ->nullable();
            $table->timestamps();

            $table->foreign('heating_id')
              ->references('id')
              ->on('heatings');
            $table->foreign('router_id')
              ->references('id')
              ->on('routers');
            $table->foreign('sim_card_id')
              ->references('id')
              ->on('sim_cards');
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
        Schema::dropIfExists('systems');
    }
}
