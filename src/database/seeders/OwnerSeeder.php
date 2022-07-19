<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            [
                'name' => 'owner',
                'email' => 'owner@test.com',
                'password' => Hash::make('owner1111'),
                'created_at' => '2022-01-01 11:11:11',
            ],
            [
                'name' => 'owner2',
                'email' => 'owner2@test.com',
                'password' => Hash::make('owner2222'),
                'created_at' => '2022-01-01 11:11:11',
            ],
            [
                'name' => 'owner3',
                'email' => 'owner3@test.com',
                'password' => Hash::make('owner3333'),
                'created_at' => '2022-01-01 11:11:11',
            ],
        ]);
    }
}
