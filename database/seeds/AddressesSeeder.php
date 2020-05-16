<?php

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
    }
}
