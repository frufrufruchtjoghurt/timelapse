<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixturesSeeder extends Seeder
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
            for ($i = 1; $i <= 3; $i++) {
                DB::table('fixtures')->insert([
                    'model' => sprintf('Testgehäuse%d', $i),
                    'purchase_date' => $faker->date(),
                ]);
            }
        }
    }
}
