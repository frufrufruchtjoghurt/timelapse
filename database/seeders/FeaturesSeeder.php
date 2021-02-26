<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturesSeeder extends Seeder
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
            for ($i = 1; $i <= 50; $i += 5) {
                DB::table('features')->insert([
                    'user_id' => $i + rand(0, 4),
                    'project_id' => random_int(1, 2),
                    'archive' => random_int(0, 1),
                    'deeplink' => random_int(0, 1),
                ]);
            }
        }
    }
}
