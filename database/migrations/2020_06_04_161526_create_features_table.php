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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->boolean('archive')
              ->default(false);
            $table->boolean('deeplink')
              ->default(false);
            $table->boolean('storage_medium')
              ->default(false);
            $table->timestamps();

            $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->cascadeOnDelete()
              ->onUpdate('cascade');
            $table->foreign('project_id')
              ->references('id')
              ->on('projects')
              ->cascadeOnDelete()
              ->onUpdate('cascade');
            $table->unique(['user_id', 'project_id']);
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
