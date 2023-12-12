<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Section1dSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections_1d')->insert([
            'id' => '1',
            'time_section' => '09:30:00',
            'open_time' => '00:01:00',
            'close_time' => '09:30:00',
        ]);
        DB::table('sections_1d')->insert([
            'id' => '2',
            'time_section' => '12:00:00',
            'open_time' => '09:31:00',
            'close_time' => '12:00:00',
        ]);
        DB::table('sections_1d')->insert([
            'id' => '3',
            'time_section' => '14:00:00',
            'open_time' => '12:01:00',
            'close_time' => '14:00:00',
        ]);
        DB::table('sections_1d')->insert([
            'id' => '4',
            'time_section' => '16:30:00',
            'open_time' => '14:01:00',
            'close_time' => '16:30:00',
        ]);
    }
}
