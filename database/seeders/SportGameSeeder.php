<?php

namespace Database\Seeders;
use App\Models\Game;
use Illuminate\Database\Seeder;

class SportGameSeeder extends Seeder
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
                'g_code'         => 'M8',
                'name'      => 'M8Sport/Mbet',
                'provider_id' => '8',
                'category_id' => '1',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'category_id' => '1',
                'img' => 'ug.png',
            ],
           
            [
                'g_code'         => 'SO',
                'name'      => 'SBO SPORTSBOOK',
                'provider_id' => '9',
                'category_id' => '1',
                'active' => 1,
                'is_hot' => 1,
                'is_new' => 1,
                'img' => 'sbo-sportbook.png',
            ],
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
           
            
           
        ];

        Game::insert($sportgames);
    }
}
