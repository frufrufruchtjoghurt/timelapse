<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SongCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $musicfile = fopen(database_path('seeders/resources/musiclist.csv'), 'r');

        while (($row = fgetcsv($musicfile)) != false) {
            if ($row[0] == 'title')
                continue;

            DB::table('songs')->insert([
                'title' => $row[0],
                'genre' => $row[1],
                'embed_tag' => $row[2],
                'for_imagefilm' => $row[3],
            ]);
        }
    }
}
