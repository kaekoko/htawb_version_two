<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CryptoOneDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($id = 1, $bet_number = 0; $id <= 9, $bet_number <= 9; $id++, $bet_number++)
        {
            $bet_number = str_pad($bet_number, 1, "0", STR_PAD_LEFT);
            DB::table('crypto_one_ds')->insert([
                'id' => $id,
                'category_id' => '3', // crypto_2d
                'bet_number' => $bet_number,
                'default_amount' => '100',
            ]);
        }
    }
}
