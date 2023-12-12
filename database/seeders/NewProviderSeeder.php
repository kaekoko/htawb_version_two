<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GameProvider;

class NewProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $providers=[
        [
            'id'=>17,
            'name' => 'CQ9',
            'p_code' => 'CQ',
            "img" => 'storage/providers/cq9.png',
        ],
    ];
    GameProvider::insert($providers);
    }
}
