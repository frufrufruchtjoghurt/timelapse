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
            $table->id();
            $table->string('name');
            $table->string('url')
              ->nullable();
            $table->date('start_date');
            $table->date('rec_end_date');
            $table->date('video_editor_send_date')
                ->nullable();
            $table->string('video_editor')
                ->nullable();
            $table->date('pub_date')
                ->nullable();
            $table->text('patch_notes')
                ->nullable();
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
