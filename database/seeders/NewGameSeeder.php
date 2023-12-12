<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
class NewGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sportgames = [
           
            [
                'g_code'         => 'WB',
                'name'      => 'WBET SPORTSBOOK',
                'provider_id' => '11',
                'category_id' => '1',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'wbet.png',
            ],
            [
                'g_code'         => 'WC',
                'name'      => 'WM CASINO',
                'provider_id' => '12',
                'category_id' => '2',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'wmcasino.png',
            ],
            [
                'g_code'         => 'DG',
                'name'      => 'Dream Gaming',
                'provider_id' => '14',
                'category_id' => '2',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'dreamgaming.png',
            ],
            [
                'g_code'         => 'UG',
                'name'      => 'United Gaming',
                'provider_id' => '16',
                'category_id' => '1',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'ug.png',
            ],
            [
                'g_code'         => 'S2',
                'name'      => 'AWC68 SEXY',
                'provider_id' => '15',
                'category_id' => '2',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'awc.png',
            ],
           
            
           
        ];

        Game::insert($sportgames);
    }
}
