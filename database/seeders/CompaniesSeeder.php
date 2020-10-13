<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Address;
use Faker\Factory;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('companies')->insert([
        ['name' => 'timelapse Systems',
        'aid' => Address::where([
          ['street', '=', 'Dr. Klöpferweg'],
          ['street_nr', '=', '5'],
          ['postcode', '=', 8130],
          ['country', '=', 'Österreich']
          ])->pluck('id')->first()],
        ['name' => 'bahamasoft',
        'aid' => Address::where([
          ['street', '=', 'Odilienweg'],
          ['street_nr', '=', '6-10'],
          ['postcode', '=', 8010],
          ['country', '=', 'Österreich']
          ])->pluck('id')->first()],
      ]);

      $faker = Factory::create();
      for ($i = 0; $i < 50; ++$i)
      {
        DB::table('companies')->insert([
          'name' => $faker->company,
          'aid' => random_int(3, 52)
        ]);
      }
    }
}