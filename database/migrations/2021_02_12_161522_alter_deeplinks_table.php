<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeeplinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deeplinks', function (Blueprint $table) {
            $table->renameColumn('pid', 'project_id');
            $table->renameColumn('token', 'symlink');
            $table->removeColumn('exp_date');
            $table->removeColumn('time');
            $table->removeColumn('scheduled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deeplinks', function (Blueprint $table) {
            $table->renameColumn('project_id', 'pid');
            $table->renameColumn('symlink', 'token');
            $table->dateTime('exp_date');
            $table->time('time')
                ->nullable();
            $table->boolean('scheduled')
                ->default(false);
        });
    }
}
