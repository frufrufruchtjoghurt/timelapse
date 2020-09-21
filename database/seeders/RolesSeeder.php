<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert([
        [
          'name' => 'admin',
          'description' => 'Administrator with all rights'
        ],
        [
          'name' => 'manager',
          'description' => 'Manager responsible for user and project creation and management'
        ],
        [
          'name' => 'basic',
          'description' => 'Basic user with limited access'
        ]
      ]);
    }
}
