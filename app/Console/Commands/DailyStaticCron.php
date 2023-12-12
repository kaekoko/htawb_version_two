<?php

namespace App\Console\Commands;

use App\Helper\helper;
use App\Models\DailyStatic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyStaticCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily_static:cron';

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
        $diffWithGMT=6*60*60+30*60;
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $daily = helper::daily_statics($curdate);
        $daily_static = new DailyStatic();
        $daily_static->all_bet_amount = $daily->getData()->all_bet_amounts;
        $daily_static->total_reward = $daily->getData()->total_reward;
        $daily_static->profit = $daily->getData()->profit;
        $daily_static->user_referral = $daily->getData()->user_refer_total;
        $daily_static->date = $curdate;
        $daily_static->save();

        Log::info('Daily Static create success');
    }
}
