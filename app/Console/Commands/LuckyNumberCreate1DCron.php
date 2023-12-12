<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Invoker\invoke1D;
use Illuminate\Console\Command;

class LuckyNumberCreate1DCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lucky_number_1d:cron';

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
        // $insert_two_d = invoke1D::insertLuckyNumber1D($now->format('h:i A'));
    }
}
