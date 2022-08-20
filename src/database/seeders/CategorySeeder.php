<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_categories')->insert([
            [
                'name' => '自転車',
                'sort_order' => 1,
            ],
            [
                'name' => 'キャンプ',
                'sort_order' => 2,
            ],
            [
                'name' => 'スノーボード',
                'sort_order' => 3
            ],
        ]);

        DB::table('secondary_categories')->insert([
            [
                'primary_category_id' => 1,
                'name' => 'ロードバイク',
                'sort_order' => 1,
            ],
            [
                'primary_category_id' => 1,
                'name' => 'クロスバイク',
                'sort_order' => 2,
            ],
            [
                'primary_category_id' => 1,
                'name' => 'マウンテンバイク',
                'sort_order' => 3,
            ],
            [
                'primary_category_id' => 2,
                'name' => 'スノーピーク',
                'sort_order' => 4,
            ],
            [
                'primary_category_id' => 2,
                'name' => 'コールマン',
                'sort_order' => 5,
            ],
            [
                'primary_category_id' => 2,
                'name' => 'イグニオ',
                'sort_order' => 6,
            ],
            [
                'primary_category_id' => 3,
                'name' => 'バートン',
                'sort_order' => 7
            ],
            [
                'primary_category_id' => 3,
                'name' => 'サロモン',
                'sort_order' => 8
            ],
            [
                'primary_category_id' => 3,
                'name' => 'K2',
                'sort_order' => 9
            ],
        ]);
    }
}
