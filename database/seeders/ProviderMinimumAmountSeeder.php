<?php

namespace Database\Seeders;

use App\Models\GameProvider;
use App\Models\ProviderMinimumAmount;
use Illuminate\Database\Seeder;

class ProviderMinimumAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game_providers = GameProvider::all();
        foreach ($game_providers as $value) {
            ProviderMinimumAmount::create([
                'provider_id' => $value->id,
                'minimum_amount' => 0
            ]);
        }
    }
}
