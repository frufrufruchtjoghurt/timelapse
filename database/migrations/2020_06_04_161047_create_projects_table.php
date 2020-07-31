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
            $table->date('start_date');
            $table->boolean('inactive')
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
