<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supply_unit_id');
            $table->unsignedBigInteger('project_id');
            $table->timestamps();

            $table->foreign('supply_unit_id')
                ->references('id')
                ->on('supply_units')
                ->onUpdate('cascade');
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onUpdate('cascade');
            $table->unique(['supply_unit_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_systems');
    }
}
