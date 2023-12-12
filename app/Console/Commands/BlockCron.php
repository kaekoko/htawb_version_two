<?php

namespace App\Console\Commands;

use App\Models\Section;
use App\Models\NumberSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BlockCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $timing = gmdate('s', time() + $diffWithGMT);

        if (number_format($timing) >= 0 && number_format($timing) <= 2) {
            $times = Section::where("is_open", 1)->get();
            foreach ($times as $time) {
                $block = new NumberSetting();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }

            Log::info('Reset Block');
        }
    }
}
