<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSystemsSeeder extends Seeder
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
                DB::table('project_systems')->insert([
                    'supply_unit_id' => $i + 1,
                    'project_id' => $i,
                ]);
            }
        }
    }
}
