<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Section3dSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections_3d')->insert([
            'id' => '1',
            'time' => '15:00:00',
            'close_time' => '12:00:00',
            'date' => '01',
        ]);
        DB::table('sections_3d')->insert([
            'id' => '2',
            'time' => '15:00:00',
            'close_time' => '12:00:00',
            'date' => '16',
        ]);
    }
}
