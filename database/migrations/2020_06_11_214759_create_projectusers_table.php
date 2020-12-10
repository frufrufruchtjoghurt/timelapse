<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectusers', function (Blueprint $table) {
          $table->unsignedBigInteger('project_nr');
          $table->unsignedBigInteger('uid');
          $table->timestamps();

          $table->foreign('project_nr')
            ->references('id')
            ->on('projects');
          $table->foreign('uid')
            ->references('id')
            ->on('users');

          $table->primary(['uid', 'project_nr']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectusers');
    }
}
