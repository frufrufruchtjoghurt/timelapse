<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplyUnitsSeeder extends Seeder
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
                DB::table('supply_units')->insert([
                    'fixture_id' => $i,
                    'router_id' => $i,
                    'has_heating' => random_int(0, 1),
                    'has_cooling' => random_int(0, 1),
                    'serial_nr' => sprintf('SN%04d', $i),
                    'details' => sprintf('Testsystem%d', $i),
                ]);
            }
        }
    }
}
