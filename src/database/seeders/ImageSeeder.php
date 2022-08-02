<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [];
        for($i=1; $i<=3; $i++) {
            $images[] = [
                'owner_id' => 1,
                'filename' => "sample{$i}.jpg",
                'title' => null,
            ];
        }

        DB::table('images')->insert($images);
    }
}
