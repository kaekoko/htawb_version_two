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
        DB::table('categories')->truncate();
        DB::table('categories')->insert([
            'id' => '1',
            'odd' => '80',
            'name' => '2D',
            'image' => 'lottery2d.png'
        ]);
        DB::table('categories')->insert([
            'id' => '2',
            'odd' => '100',
            'name' => '3D',
            'image' => 'lottery3d.png'
        ]);
        DB::table('categories')->insert([
            'id' => '3',
            'odd' => '80',
            'name' => 'Crypto 2D',
            'image' => 'lotteryc2d.png'
        ]);

        DB::table('categories')->insert([
            'id' => '4',
            'odd' => '8',
            'name' => '1D',
            'image' => 'lottery1d.png'
        ]);

        DB::table('categories')->insert([
            'id' => '5',
            'odd' => '8',
            'name' => 'Crypto 1D',
            'image' => 'lotteryc1d.png'
        ]);
    }
}
