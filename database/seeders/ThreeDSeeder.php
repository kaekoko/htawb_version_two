<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreeDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($id = 1, $bet_number = 0; $id <= 999, $bet_number <= 999; $id++, $bet_number++)
        {
            $bet_number = str_pad($bet_number, 3, "0", STR_PAD_LEFT);
            DB::table('three_ds')->insert([
                'id' => $id,
                'category_id' => '2',
                'bet_number' => $bet_number,
            ]);
        }
    }
}
