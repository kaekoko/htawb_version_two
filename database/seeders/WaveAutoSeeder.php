<?php

namespace Database\Seeders;

use App\Models\WaveOpenOff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WaveAutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wave_open_off')->insert([
            'id' => 1,
            'icasino_wave' => '0',
            'myvip_wave' => '0'
        ]);
    }
}
