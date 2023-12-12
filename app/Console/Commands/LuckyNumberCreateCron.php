<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LuckyNumberCreateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luckynumber:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Lucky Number Null';

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
        $diffWithGMT=66060+30*60;
        $date = gmdate('Y-m-d', time()+$diffWithGMT);
        $times = ['9:30 AM', '12:00 PM', '2:00 PM', '4:30 PM', '8:00 PM'];
        foreach($times as $time){
            DB::table('lucky_numbers')->insert([
                'twod_number' => '-',
                'record_time' => $time,
                'record_date' => $date
            ]);
        }
    }
}
