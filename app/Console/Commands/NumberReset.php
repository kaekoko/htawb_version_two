<?php

namespace App\Console\Commands;

use App\Models\Section;
use App\Models\CustomRecord;
use App\Models\CustomRecordCrypto2D;
use App\Models\NumberSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NumberReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'number:reset';

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
        $date = gmdate('Y-m-d', time()+$diffWithGMT);
        // For 2D
        $customs = CustomRecord::get();
        foreach($customs as $custom){
            $custom->record_date = $date;
            $custom->twod_number = '-';
            $custom->save();
        }
        // For Crypto 2D
        $customs_c2d = CustomRecordCrypto2D::get();
        foreach($customs_c2d as $custom){
            $custom->record_date = $date;
            $custom->twod_number = '-';
            $custom->save();
        }
        Log::info('Reset Date');
    }
}
