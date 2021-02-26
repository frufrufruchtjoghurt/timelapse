<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CamerasSeeder extends Seeder
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
            $id = 1;
            for ($i = 1; $i <= 5; $i++) {
                DB::table('cameras')->insert([
                    'serial_nr' => sprintf('SN%04d', $i),
                    'model' => sprintf('Testcam%d', $i),
                    'name' => sprintf('cam%03d', $i),
                    'purchase_date' => $faker->date(),
                    'supply_unit_id' => $id,
                ]);

                if ($i % 2 == 0)
                    $id++;
            }
        }
    }
}
