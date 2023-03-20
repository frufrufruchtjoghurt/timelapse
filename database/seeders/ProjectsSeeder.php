<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.debug') == true) {
            $faker = Factory::create();
            for ($i = 1; $i <= 2; $i++) {
                DB::table('projects')->insert([
                    'id' => $i,
                    'name' => sprintf('Testprojekt%d', $i),
                    'url' => 'test.url',
                    'start_date' => sprintf('2021-0%d-12', $i),
                    'rec_end_date' => sprintf('2021-0%d-18', $i + 1),
                ]);
            }
        }
    }
}
