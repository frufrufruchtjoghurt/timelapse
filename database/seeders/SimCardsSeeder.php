<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimCardsSeeder extends Seeder
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
            for ($i = 1; $i <= 5; $i++) {
                DB::table('sim_cards')->insert([
                    'telephone_nr' => $faker->phoneNumber,
                    'contract' => sprintf('Testsim%d', $i),
                    'purchase_date' => $faker->date(),
                ]);
            }
        }
    }
}
