<?php

namespace App\Console\Commands;

use App\Invoker\invokeAll;
use App\Events\NumberEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SuperAdmin\LiveController;

class LiveCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:cron';

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
        // $result = (new LiveController)->tingo_channel();

        $tingo = json_decode(file_get_contents("https://api.thaistock2d.com/live"), true);
        $result = $tingo['live'];
        event(new NumberEvent($result));

        Log::info('tingo work');
    }
}
