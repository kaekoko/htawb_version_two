<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\NumberSetting;
use Illuminate\Database\Seeder;

class HotBlock extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $times = Section::where("is_open", 1)->get();
        foreach ($times as $time) {
            $block = new NumberSetting();
            $block->section = date("h:i A", strtotime($time->time_section));
            $block->type = 'block';
            $block->block_number = NULL;
            $block->save();
        }

        foreach ($times as $time) {
            $hot = new NumberSetting();
            $hot->section = date("h:i A", strtotime($time->time_section));
            $hot->type = 'hot';
            $hot->hot_number = '-';
            $hot->hot_amount = NULL;
            $hot->save();
        }
    }
}
