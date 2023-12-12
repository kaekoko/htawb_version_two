<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShamelessGameCategory;
use Illuminate\Support\Facades\Schema;

class ShamelessGameCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        ShamelessGameCategory::truncate();

        Schema::enableForeignKeyConstraints();
        
        $categories =  [
            [
                'id' => 1,
                'code' => 1,
                'name' => 'Slot',
                'image' => 'Slot.png'
            ],
         
            [
                'id' => 2,
                'code' => 2,
                'name' => 'LIVE-CASINO',
                'image' => 'live_casino.png'
            ],
            [
                'id' => 3,
                'code' => 3,
                'name' => 'Sport Book ',
                'image' => 'Sport.png'
            ],
            [
                'id' => 6,
                'code' => 6,
                'name' => 'Card Game',
                'image' => 'card_game.png'
            ],
            [
                'id' => 8,
                'code' => 8,
                'name' => 'Fishing ',
                'image' => 'Fishing.png'
            ],
            [
                'id' => 9,
                'code' => 9,
                'name' => 'other ',
                'image' => 'other.png'
            ],
         
        ];
        // ShamelessGameCategory::where('active', 1)->delete();

        foreach ($categories as $category) {
            $imagePath = ($category['image']);
            $category['image'] = $imagePath;

            $g = ShamelessGameCategory::create($category);
        }
    }
}
