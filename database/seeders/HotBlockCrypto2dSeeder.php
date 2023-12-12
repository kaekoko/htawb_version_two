<?php

namespace Database\Seeders;

use App\Models\NumberSettingCrypto2d;
use App\Models\SectionCrypto2d;
use Illuminate\Database\Seeder;

class HotBlockCrypto2dSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $times = SectionCrypto2d::get();
        foreach($times as $time){
            $block = new NumberSettingCrypto2d();
            $block->section = date("h:i A", strtotime($time->time_section));
            $block->type = 'block';
            $block->block_number = NULL;
            $block->save();
        }

        foreach($times as $time){
            $hot = new NumberSettingCrypto2d();
            $hot->section = date("h:i A", strtotime($time->time_section));
            $hot->type = 'hot';
            $hot->hot_number = '-';
            $hot->hot_amount = NULL;
            $hot->save();
        }
    }
}
