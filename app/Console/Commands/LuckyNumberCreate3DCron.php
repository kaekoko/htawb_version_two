<?php

namespace App\Console\Commands;

use App\Models\LuckyNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LuckyNumberCreate3DCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lucky_number_3d:cron';

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
        $threed_number = rand(100,999);
        $section = date('h:i A');
        $date = date('Y-m-d');
        $lucky_number = New LuckyNumber();
        $lucky_number->lucky_number = $threed_number;
        $lucky_number->section = $section;
        $lucky_number->category_id = 2;
        $lucky_number->create_date = $date;
        $lucky_number->save();
        Log::info('Three D insert in lucky number');
    }
}
