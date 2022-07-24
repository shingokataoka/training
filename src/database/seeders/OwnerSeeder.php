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
        $rows = [];
        for ($i=1; $i<=10; $i++) {
            $rows[] = [
                'name' => "owner{$i}",
                'email' => "owner{$i}@test.com",
                'password' => Hash::make("owner{$i}{$i}{$i}{$i}"),
                'created_at' => '2022-01-01 11:11:11',
            ];
        }

        DB::table('owners')->insert($rows);
    }
}
