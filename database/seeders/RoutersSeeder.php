<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoutersSeeder extends Seeder
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
                DB::table('routers')->insert([
                    'serial_nr' => sprintf('SN%04d', $i),
                    'model' => sprintf('Testrouter%d', $i),
                    'name' => sprintf('router%03d', $i),
                    'ssid' => 'RUT_' . Str::random(4),
                    'psk' => Str::random(10),
                    'sim_card_id' => $i,
                    'purchase_date' => $faker->date(),
                ]);
            }
        }
    }
}
