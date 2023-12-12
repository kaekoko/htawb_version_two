<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpinWheelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($id = 1; $id <= 8; $id++) {
            DB::table('spin_wheels')->insert([
                'id' => $id,
                'type' => 'wheel'
            ]);
        }
        DB::table('spin_wheels')->insert([
            'id' => 9,
            'type' => 'mesg'
        ]);
        DB::table('spin_wheels')->insert([
            'id' => 10,
            'type' => 'wheel_on'
        ]);
        DB::table('spin_wheels')->insert([
            'id' => 11,
            'type' => 'lvl_2_off'
        ]);
        DB::table('spin_wheels')->insert([
            'id' => 12,
            'type' => 'free_on'
        ]);
    }
}
