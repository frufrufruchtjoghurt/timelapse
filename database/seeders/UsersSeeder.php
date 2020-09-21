<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\Role;
use Faker\Factory;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        ['title' => 'Mag.',
          'gender' => 'Frau',
          'first_name' => 'Judith',
          'last_name' => 'Pölzl',
          'cid' => Company::where('name', 'timelapse Systems')->pluck('id')->first(),
          'rid' => Role::where('name', 'manager')->pluck('id')->first(),
          'email' => 'judith.poelzl@tls.test',
          'password' => bcrypt('JP1234-timelapse')
        ],
        ['title' => NULL,
          'gender' => 'Herr',
          'first_name' => 'Markus',
          'last_name' => 'Fruhmann',
          'cid' => Company::where('name', 'bahamasoft')->pluck('id')->first(),
          'rid' => Role::where('name', 'admin')->pluck('id')->first(),
          'email' => 'markus@fruhmann.dev',
          'password' => bcrypt('MF1234-timelapse')
        ],
        ['title' => 'Dipl.-Ing.',
          'gender' => 'Herr',
          'first_name' => 'Reinhard',
          'last_name' => 'Urban',
          'cid' => Company::where('name', 'bahamasoft')->pluck('id')->first(),
          'rid' => Role::where('name', 'admin')->pluck('id')->first(),
          'email' => 'r.urban@bahamasoft.test',
          'password' => bcrypt('RU1234-timelapse')
        ]
      ]);

      $faker = Factory::create();
      for ($i = 0; $i < 100; ++$i)
      {
        $rand = random_int(0, 1);

        if ($rand)
        {
          DB::table('users')->insert([
            'title' => 'Ing.',
            'gender' => 'Frau',
            'first_name' => $faker->firstNameFemale,
            'last_name' => $faker->lastName,
            'cid' => random_int(3, 52),
            'rid' => Role::where('name', 'basic')->pluck('id')->first(),
            'email' => $faker->companyEmail,
            'password' => bcrypt('TEST1234-timelapse')
          ]);
        }
        else
        {
          DB::table('users')->insert([
            'title' => 'Ing.',
            'gender' => 'Frau',
            'first_name' => $faker->firstNameMale,
            'last_name' => $faker->lastName,
            'cid' => random_int(3, 52),
            'rid' => Role::where('name', 'basic')->pluck('id')->first(),
            'email' => $faker->companyEmail,
            'password' => bcrypt('TEST1234-timelapse')
          ]);
        }
      }
    }
}
