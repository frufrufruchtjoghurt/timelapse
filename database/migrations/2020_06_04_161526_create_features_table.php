<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->unsignedBigInteger('uid');
            $table->unsignedBigInteger('pid');
            $table->boolean('archive')
              ->default(false);
            $table->boolean('deeplink')
              ->default(false);
            $table->boolean('storage_medium')
              ->default(false);
            $table->timestamps();

            $table->foreign('uid')
              ->references('id')
              ->on('users')
              ->cascadeOnDelete()
              ->onUpdate('cascade');
            $table->foreign('pid')
              ->references('project_nr')
              ->on('projects')
              ->cascadeOnDelete()
              ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
}
