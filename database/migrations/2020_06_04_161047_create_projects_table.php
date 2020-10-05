<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('project_nr')
              ->unique()
              ->primary();
            $table->string('name');
            $table->unsignedBigInteger('cid');
            $table->unsignedBigInteger('sid');
            $table->string('vpn_ip')
              ->nullable();
            $table->string('longitude')
              ->nullable();
            $table->string('latitude')
              ->nullable();
            $table->string('film_studio')
              ->nullable();
            $table->date('start_date');
            $table->date('end_date')
              ->nullable();
            $table->boolean('invisible')
              ->default(false);
            $table->boolean('setup_done')
              ->default(false);
            $table->boolean('film_done')
              ->default(false);
            $table->boolean('film_send')
              ->default(false);
            $table->boolean('completed')
              ->default(false);
            $table->dateTime('inactivity_date')
              ->nullable();
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
        Schema::dropIfExists('projects');
    }
}
