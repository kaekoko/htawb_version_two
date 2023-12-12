<?php

namespace App\Console\Commands;

use App\Helper\helper;
use App\Models\DailyStaticCrypto2D;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyStaticC2DCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily_static_c2d:cron';

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
        $diffWithGMT=6*60*60+30*60; // GMT + 06:30 ( Myanmar Time Zone )
        $curdate = gmdate('Y-m-d', time()+$diffWithGMT);
        $daily = helper::daily_statics_c2d($curdate);
        $daily_static = new DailyStaticCrypto2D();
        $daily_static->all_bet_amount = $daily->getData()->all_bet_amounts;
        $daily_static->total_reward = $daily->getData()->total_reward;
        $daily_static->profit = $daily->getData()->profit;
        $daily_static->user_referral = $daily->getData()->user_refer_total;
        $daily_static->date = $curdate;
        $daily_static->save();

        Log::info('Crypto 2D Daily Static create success');
    }
}
