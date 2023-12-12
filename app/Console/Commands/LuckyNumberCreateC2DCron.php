<?php

namespace App\Console\Commands;

use App\Invoker\invokeC2D;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LuckyNumberCreateC2DCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lucky_number_c2d:cron';

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
        $now = Carbon::now();
        invokeC2D::insertLuckyNumberC2D($now->format('h:i A'));
    }
}
