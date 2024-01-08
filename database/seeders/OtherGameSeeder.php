<?php

namespace Database\Seeders;

use App\Models\ShamelessGame;
use Illuminate\Database\Seeder;

class OtherGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $othergames = [
            [
                'g_code' => '1020-WM_CASINO',
                'provider_id' => 7,
                'category_id' => 2,
                'cate_code' => 2,
                'p_code' => 1020,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/wm-casino_game.jpg",
                'name' => 'WM Casino',
            ],
            [
                'g_code' => '1001-ASIA_GAMING',
                'provider_id' => 1,
                'category_id' => 2,
                'cate_code' => 2,
                'p_code' => 1001,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/asia_gaming_game.png",
                'name' => 'Asia Gaming Live casino',
            ],

            [
                'g_code' => '610001',
                'provider_id' => 4,
                'category_id' => 3,
                'cate_code' => 3,
                'p_code' => 1012,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/sbo_game.jpg",
                'name' => 'SBO SPORT BETTING',
            ],

            [
                'g_code' => '234343',
                'provider_id' => 10,
                'category_id' => 3,
                'cate_code' => 3,
                'p_code' => 1036,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/ug_sport.png",
                'name' => 'UG Sport',
            ],

            [
                'g_code' => '1052-DREAM_GAMING',
                'provider_id' => 14,
                'category_id' => 2,
                'cate_code' => 2,
                'p_code' => 1052,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/dream-gaming_game.png",
                'name' => 'Dream Gaming',
            ],

            [
                'g_code' => '0',
                'provider_id' => 17,
                'category_id' => 2,
                'cate_code' => 2,
                'p_code' => 1080,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/provider/venus_game.png",
                'name' => 'Venus',
            ],

            [
                'g_code' => '0',
                'provider_id' => 8,
                'category_id' => 2,
                'cate_code' => 2,
                'p_code' => 1022,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://backend.myvipmm.com/othergames/sexy-gaming.jpg",
                'name' => 'Sexy Gaming',
            ],

            [
                'g_code' => '0',
                'provider_id' => 24,
                'category_id' => 9,
                'cate_code' => 9,
                'p_code' => 1033,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://admin.htawb2d3d.com/provider/svfighting.png",
                'name' => 'SV Fighting',
            ],


            [
                'g_code' => '0',
                'provider_id' => 25,
                'category_id' => 3,
                'cate_code' => 3,
                'p_code' => 1046,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://admin.htawb2d3d.com/provider/ibc.png",
                'name' => 'IBC GAMING',
            ],

            [
                'g_code' => '0',
                'provider_id' => 31,
                'category_id' => 3,
                'cate_code' => 3,
                'p_code' => 1081,
                'active' => 1,
                'is_hot' => 0,
                'is_new' => 0,
                'html_type' => 0,
                'image' => "https://admin.htawb2d3d.com/provider/bti.png",
                'name' => 'BTI Gaming',
            ],

        ];

        ShamelessGame::insert($othergames);

    }
}
