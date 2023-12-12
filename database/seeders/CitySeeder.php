<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            'id' => '1',
            'name' => 'Yangon'
        ]);
        DB::table('cities')->insert([
            'id' => '2',
            'name' => 'Mandalay'
        ]);
        DB::table('cities')->insert([
            'id' => '3',
            'name' => 'NayPyiTaw'
        ]);
    }
}
