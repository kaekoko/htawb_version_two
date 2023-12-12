<?php

namespace Database\Seeders;

use App\Models\ShamelessGame;
use App\Models\ShamelessGameProvider;
use Illuminate\Database\Seeder;

class ShamelessGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShamelessGame::truncate();
        $providers = ShamelessGameProvider::get();
        foreach ($providers as $key => $provider) {
            foreach ($provider->categories as $key => $category) {
                $data = GetGameList($provider->p_code, $category->code, 0);
                $jsonData = $data->json();

                if ($jsonData['ErrorCode'] == 0) {
                    $lists = $jsonData['ProviderGames'];
                    foreach ($lists as $key => $decode) {
                        $game = new ShamelessGame();
                        $game->g_code = $decode['GameCode'];
                        $game->provider_id = $provider->id;
                        $game->active = 1;
                        $game->is_hot = 0;
                        $game->is_new = 0;
                        $game->cate_code = $category->code;
                        $game->p_code = $provider->p_code;
                        $game->category_id = $category->id;
                        $game->html_type = $decode['Category'];
                        $game->image = $decode['ImageUrl'];
                        $game->name = $decode['GameName'];
                        $game->save();
                    }
                }
            }
        }
    }
}
