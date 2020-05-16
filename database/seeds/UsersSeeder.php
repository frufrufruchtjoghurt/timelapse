<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Company;

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
        ['role' => 'manager',
        'title' => 'Mag.',
        'gender' => 'Frau',
        'first_name' => 'Judith',
        'last_name' => 'Pölzl',
        'cid' => Company::where('name', 'timelapse Systems')->pluck('id')->first(),
        'email' => 'judith.poelzl@tls.test',
        'password' => bcrypt('JP1234-timelapse')
      ],
      ['role' => 'admin',
        'title' => NULL,
        'gender' => 'Herr',
        'first_name' => 'Markus',
        'last_name' => 'Fruhmann',
        'cid' => NULL,
        'email' => 'markus@fruhmann.dev',
        'password' => bcrypt('MF1234-timelapse')
      ],
      ['role' => 'admin',
        'title' => 'Dipl.-Ing.',
        'gender' => 'Herr',
        'first_name' => 'Reinhard',
        'last_name' => 'Urban',
        'cid' => Company::where('name', 'bahamasoft')->pluck('id')->first(),
        'email' => 'r.urban@bahamasoft.test',
        'password' => bcrypt('RU1234-timelapse')
        ]
      ]);
    }
}
