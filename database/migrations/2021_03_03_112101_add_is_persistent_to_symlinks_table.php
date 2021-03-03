<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPersistentToSymlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symlinks', function (Blueprint $table) {
            $table->boolean('is_persistent')
                ->default(false);
            $table->boolean('is_movie')
                ->default(false);
            $table->foreignId('camera_id')
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symlinks', function (Blueprint $table) {
            $table->removeColumn('is_persistent');
            $table->removeColumn('is_movie');
            $table->removeColumn('camera_id');
        });
    }
}
