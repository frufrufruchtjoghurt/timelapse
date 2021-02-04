<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
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
                'company_id' => Company::where('name', 'timelapse Systems')->pluck('id')->first(),
                'email' => 'judith.poelzl@timelapsesystems.at',
                'password' => bcrypt('JP1234-timelapse'),
                'phone_nr' => '43/69981930597',
                'permissions' => json_encode([
                    "platform.index" => true,
                    "manager" => true,
                ]),
            ],
            ['title' => NULL,
                'gender' => 'Herr',
                'first_name' => 'Markus',
                'last_name' => 'Fruhmann',
                'company_id' => Company::where('name', 'bahamasoft')->pluck('id')->first(),
                'email' => 'markus@fruhmann.dev',
                'password' => bcrypt('MF1234-timelapse'),
                'phone_nr' => '43/6766769120',
                'permissions' => json_encode([
                    "platform.index" => true,
                    'admin' => true,
                ]),
            ],
            ['title' => 'Dipl.-Ing.',
                'gender' => 'Herr',
                'first_name' => 'Reinhard',
                'last_name' => 'Urban',
                'company_id' => Company::where('name', 'bahamasoft')->pluck('id')->first(),
                'email' => 'bahamas@gestalter.at',
                'password' => bcrypt('bananas'),
                'phone_nr' => '43/6764715753',
                'permissions' => json_encode([
                    "platform.index" => true,
                    'admin' => true,
                ]),
            ]
        ]);

        if (config('app.debug') == true)
        {
            $faker = Factory::create();
            for ($i = 0; $i < 100; ++$i) {
                $rand = random_int(0, 1);

                if ($rand) {
                    DB::table('users')->insert([
                        'title' => 'Ing.',
                        'gender' => 'Frau',
                        'first_name' => $faker->firstNameFemale,
                        'last_name' => $faker->lastName,
                        'company_id' => random_int(3, 52),
                        'email' => $faker->companyEmail,
                        'password' => bcrypt('TEST1234-timelapse'),
                        'phone_nr' => '518/98747894512',
                        'permissions' => json_encode(["platform.index" => true]),
                    ]);
                } else {
                    DB::table('users')->insert([
                        'title' => 'Ing.',
                        'gender' => 'Herr',
                        'first_name' => $faker->firstNameMale,
                        'last_name' => $faker->lastName,
                        'company_id' => random_int(3, 52),
                        'email' => $faker->companyEmail,
                        'password' => bcrypt('TEST1234-timelapse'),
                        'phone_nr' => '44/4567895461',
                        'permissions' => json_encode(["platform.index" => true]),
                    ]);
                }
            }
        }
    }
}
