<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('addresses')->insert([
        ['street' => 'Dr. Klöpferweg',
        'street_nr' => '5',
        'staircase' => NULL,
        'door_nr' => NULL,
        'postcode' => 8130,
        'city' => 'Frohnleiten',
        'region' => 'Steiermark',
        'country' => 'Österreich'],
        ['street' => 'Odilienweg',
        'street_nr' => '6-10',
        'staircase' => 2,
        'door_nr' => 42,
        'postcode' => 8010,
        'city' => 'Graz',
        'region' => NULL,
        'country' => 'Österreich']
      ]);

      $faker = Factory::create();
      for ($i = 0; $i < 50; ++$i)
      {
        DB::table('addresses')->insert([
          'street' => $faker->streetName,
          'street_nr' => $faker->streetAddress,
          'staircase' => NULL,
          'door_nr' => NULL,
          'postcode' => random_int(1000, 90000),
          'city' => $faker->city,
          'region' => $faker->word,
          'country' => $faker->country
        ]);
      }
    }
}
