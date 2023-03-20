<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AddressesSeeder::class,
            CompaniesSeeder::class,
            UsersSeeder::class,
            SimCardsSeeder::class,
            RoutersSeeder::class,
            FixturesSeeder::class,
            SupplyUnitsSeeder::class,
            CamerasSeeder::class,
            ProjectsSeeder::class,
            ProjectSystemsSeeder::class,
            FeaturesSeeder::class,
            SongCsvSeeder::class,
        ]);
    }
}
