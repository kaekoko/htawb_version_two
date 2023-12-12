<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TwoDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($id = 1, $bet_number = 0; $id <= 99, $bet_number <= 99; $id++, $bet_number++)
        {
            $bet_number = str_pad($bet_number, 2, "0", STR_PAD_LEFT);
            DB::table('two_ds')->insert([
                'id' => $id,
                'category_id' => '1',
                'bet_number' => $bet_number,
                'default_amount' => '100',
            ]);
        }
    }
}
