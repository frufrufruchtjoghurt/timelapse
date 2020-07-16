<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeeplinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deeplinks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pid');
            $table->string('token');
            $table->dateTime('exp_date');
            $table->time('time')
              ->nullable();
            $table->boolean('scheduled')
              ->default(false);
            $table->timestamps();

            $table->foreign('pid')
              ->references('project_nr')
              ->on('projects')
              ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deeplinks');
    }
}
