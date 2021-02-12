<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatestOnSymlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symlinks', function (Blueprint $table) {
            $table->boolean('is_latest')
                ->default(false);
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
            $table->removeColumn('is_latest');
        });
    }
}
