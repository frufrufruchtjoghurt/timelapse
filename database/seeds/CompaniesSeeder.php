<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Address;

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
    }
}
