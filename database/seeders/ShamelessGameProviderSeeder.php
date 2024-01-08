<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShamelessGameProvider;
use Illuminate\Support\Facades\Schema;

class ShamelessGameProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        ShamelessGameProvider::truncate();

        Schema::enableForeignKeyConstraints();
        $array = [

            [
                'id' => 1,
                "name" => 'Asia Gaming',
                "p_code" => 1001,
                'hot' => 1,
                'image' => 'asia_gaming.png',
                'sec_image' => ''
            ],
            [
                'id' => 2,
                'name' => 'Pragmatic Play',
                'p_code' => 1006,
                'hot' => 1,
                'image'  => 'pp_slot.png',
                'sec_image' => 'pp_casino.png'
            ],
            [
                'id' => 3,
                "name" => 'CQ9',
                "p_code" => 1009,
                'hot' => 1,
                'image'  => 'cq9_fish.png',
                'sec_image' => 'cq9_fish.png'
            ],
            [
                'id' => 4,
                'name' => 'SBO',
                'p_code' => 1012,
                'hot' => 1,
                'image'  => 'sbo_two.png',
                'sec_image' => ''
            ],
            [
                'id' => 5,
                'name' => 'Jocker',
                'p_code' => 1013,
                'hot' => 1,
                'image'  => 'joker.png',
                'sec_image' => ''
            ],
            [
                'id' => 6,
                'name' => 'Live 22',
                'p_code' => 1018,
                'hot' => 1,
                'image'  => 'Live22.png',
                'sec_image' => ''
            ],
            [
                'id' => 7,
                'name' => 'WM Casino',
                'p_code' => 1020,
                'hot' => 1,
                'image' => 'wm_casino.png',
                'sec_image' => ''
            ],
            [
                'id' => 8,
                'name' => 'Sexy Gaming',
                'p_code' => 1022,
                'hot' => 1,
                'image' => 'sexy_gaming.png',
                'sec_image' => ''
            ],
            [
                'id' => 9,
                'name' => 'Spade Gaming',
                'p_code' => 1034,
                'hot' => 1,
                'image'  => 'spade_slot.png',
                'sec_image' => ''
            ],
            [
                'id' => 10,
                'name' => 'UG Sport',
                'p_code' => 1036,
                'hot' => 1,
                'image'  => 'ug_sport.png',
                'sec_image' => ''
            ],
            [
                'id' => 11,
                'name' => 'Habanero',
                'p_code' => 1041,
                'hot' => 1,
                'image'  => 'habanero.png',
                'sec_image' => ''
            ],
            [
                'id' => 12,
                'name' => 'Evoplay',
                'p_code' => 1049,
                'hot' => 1,
                'image'  => 'evo_play.png',
                'sec_image' => ''
            ],
            [
                'id' => 13,
                'name' => 'PlayStar',
                'p_code' => 1050,
                'hot' => 1,
                'image'  => 'play_star.png',
                'sec_image' => 'not.jpg'
            ],
            [
                'id' => 14,
                'name' => 'Dream Gaming',
                'p_code' => 1052,
                'hot' => 1,
                'image' => 'dream_gaming.png',
                'sec_image' => ''
            ],
            [
                'id' => 15,
                'name' => 'Red Rake',
                'p_code' => 1067,
                'hot' => 1,
                'image' => 'red_rake.png',
                'sec_image' => ''
            ],
            [
                'id' => 16,
                'name' => 'Fachai',
                'p_code' => 1079,
                'hot' => 1,
                'image'  => 'fachai.png',
                'sec_image' => ''
            ],
            [
                'id' => 17,
                'name' => 'Venus',
                'p_code' => 1080,
                'hot' => 1,
                'image'  => 'venus.png',
                'sec_image' => ''
            ],
            [
                'id' => 18,
                'name' => 'JDB',
                'p_code' => 1085,
                'hot' => 1,
                'image'  => 'jdb_slot.png',
                'sec_image' => ''
            ],
            [
                'id' => 19,
                'name' => 'Simple Play',
                'p_code' => 1089,
                'hot' => 1,
                'image'  => 'simple_play.png',
                'sec_image' => ''
            ],
            [
                'id' => 20,
                'name' => 'Jili',
                'p_code' => 1091,
                'hot' => 1,
                'image'  => 'jili_slot.png',
                'sec_image' => 'jili_fish.png'
            ],
            [
                'id' => 21,
                'name' => 'Vivo Gaming',
                'p_code' => 1035,
                'hot' => 1,
                'image' => 'vivo.png',
                'sec_image' => ''
            ],
            [
                'id' => 22,
                'name' => 'Dragon Soft',
                'p_code' => 1014,
                'hot' => 1,
                'image' => 'dragon_soft.png',
                'sec_image' => ''
            ],
            [
                'id' => 23,
                'name' => 'King855',
                'p_code' => 1038,
                'hot' => 1,
                'image' => 'king855.png',
                'sec_image' => ''
            ],
            [
                'id' => 24,
                'name' => 'SV388 Cock Fighting',
                'p_code' => 1033,
                'hot' => 1,
                'image' => 'svfighting.png',
                'sec_image' => ''
            ],

            [
                'id' => 25,
                'name' => 'IBC',
                'p_code' => 1046,
                'hot' => 1,
                'image' => 'ibc.png',
                'sec_image' => ''
            ],
            
            [
                'id' => 26,
                'name' => 'IBC',
                'p_code' => 1046,
                'hot' => 1,
                'image' => 'ibc.png',
                'sec_image' => ''
            ],

            [
                'id' => 27,
                'name' => 'Advant Play',
                'p_code' => 1084,
                'hot' => 1,
                'image' => 'advantPlay.png',
                'sec_image' => ''
            ], 

            [
                'id' => 28,
                'name' => 'TrueLab',
                'p_code' => 1056,
                'hot' => 1,
                'image' => 'truelab.png',
                'sec_image' => ''
            ], 
            

            [
                'id' => 29,
                'name' => 'Net Game',
                'p_code' => 1064,
                'hot' => 1,
                'image' => 'netgame.png',
                'sec_image' => ''
            ], 

            [
                'id' => 30,
                'name' => 'Boongo',
                'p_code' => 1070,
                'hot' => 1,
                'image' => 'boongo.png',
                'sec_image' => ''
            ], 

            [
                'id' => 31,
                'name' => 'BTI',
                'p_code' => 1081,
                'hot' => 1,
                'image' => 'bti.png',
                'sec_image' => ''
            ], 

            [
                'id' => 32,
                'name' => 'Felix Gaming',
                'p_code' => 1098,
                'hot' => 1,
                'image' => 'felixgaming.png',
                'sec_image' => ''
            ], 

            [
                'id' => 33,
                'name' => 'Gss',
                'p_code' => 1015,
                'hot' => 1,
                'image' => 'gss_gaming.png',
                'sec_image' => ''
            ], 

            [
                'id' => 34,
                'name' => 'Royal Slot Gaming',
                'p_code' => 1105,
                'hot' => 1,
                'image' => 'royal.png',
                'sec_image' => ''
            ], 

            [
                'id' => 35,
                'name' => 'Red Tiger',
                'p_code' => 1110,
                'hot' => 1,
                'image' => 'redtiger.png',
                'sec_image' => ''
            ], 

            [
                'id' => 36,
                'name' => 'KA Gaming',
                'p_code' => 1102,
                'hot' => 1,
                'image' => 'ka_gaming.png',
                'sec_image' => ''
            ], 
        ];


        foreach ($array as $game) {

            $g = ShamelessGameProvider::create($game);
            switch ($game['name']) {
                case "Asia Gaming":
                    $g->categories()->sync([1,2]);
                    break;
                case "Pragmatic Play":
                    $g->categories()->sync([1, 2]);
                    break;
                case "CQ9":
                    $g->categories()->sync([1,8,9]);
                    break;
                case "SBO":
                    $g->categories()->sync([3]);
                    break;
                case "Jocker":
                        $g->categories()->sync([1]);
                        break;
                case "Live 22":
                    $g->categories()->sync([1]);
                    break;
                case "WM Casino":
                    $g->categories()->sync([2]);
                    break;
                case "Sexy Gaming":
                    $g->categories()->sync([2]);
                    break;
                case "Spade Gaming":
                    $g->categories()->sync([1,8]);
                    break;
                case "UG Sport":
                    $g->categories()->sync([3]);
                    break;
                case "Habanero":
                    $g->categories()->sync([1]);
                    break;
                case "Evoplay":
                    $g->categories()->sync([1]);
                    break;
                case "PlayStar":
                    $g->categories()->sync([1]);
                    break;
                case "Dream Gaming":
                    $g->categories()->sync([2]);
                    break;
                case "Red Rake":
                    $g->categories()->sync([1]);
                    break;
                case "Fachai":
                    $g->categories()->sync([1]);
                    break;
                case "Venus":
                    $g->categories()->sync([2]);
                    break;
                case "JDB":
                    $g->categories()->sync([1,8]);
                    break;
                case "Simple Play":
                    $g->categories()->sync([1]);
                    break;
                case "Jili":
                    $g->categories()->sync([1, 6, 8]);
                    break;
                case "Funta Gaming":
                    $g->categories()->sync([1]);
                    break;
                case "Dragon Soft":
                    $g->categories()->sync([1,6,8]);
                    break;
                case "King855":
                    $g->categories()->sync([2]);
                    break;
                case "SV388 Cock Fighting":
                    $g->categories()->sync([9]);
                    break;
                case "IBC":
                    $g->categories()->sync([3]);
                    break;

                case "Advant Play":
                    $g->categories()->sync([1]);
                    break;

                case "TrueLab":
                    $g->categories()->sync([1]);
                    break;

                case "Net Game":
                    $g->categories()->sync([1]);
                    break;

                case "Boongo":
                    $g->categories()->sync([1]);
                    break;

                case "Skywind":
                    $g->categories()->sync([1,2]);
                    break;

                case "BTI":
                    $g->categories()->sync([3]);
                    break;

                case "Felix Gaming":
                    $g->categories()->sync([1]);
                    break;
            
                case "Gss":
                    $g->categories()->sync([1]);
                    break;

                case "Royal Slot Gaming":
                    $g->categories()->sync([1]);
                    break;

                case "Red Tiger":
                    $g->categories()->sync([1]);
                    break;
                case "KA Gaming":
                    $g->categories()->sync([1]);
                    break;

                    
            }

            $imagePath = 'provider/' . $game['image'];
            $sec_image_path = 'provider/' . $game['sec_image'];
            $g->image = $imagePath;
            $g->sec_image = $sec_image_path;
            $g->save();
        }
    }
}
