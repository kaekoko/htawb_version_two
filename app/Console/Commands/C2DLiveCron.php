<?php

namespace App\Console\Commands;

use App\Http\Controllers\FirebaseController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class C2DLiveCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c2d-live:cron';

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
        $ch = curl_init();
        $url = 'https://api1.binance.com/api/v3/ticker/bookTicker?symbol=BTCUSDT';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        $de = json_decode($data);
        $final = $de;
        if (strpos($final->bidPrice, '.') != false) {
            $pre_ex = explode('.', $final->bidPrice);
            if (strlen($pre_ex[1]) > 1) {
                $bidprice = number_format($final->bidPrice, 2, '.', '');
            } else {
                $bidprice = $pre_ex[0] . '.' . $pre_ex[1] . '0';
            }
        } else {
            $bidprice = $final->bidPrice . '.00';
        }

        $ex_one = explode('.', $bidprice);
        $first = substr($ex_one[0], -1);
        $second = substr($ex_one[1], -1);

        // Firebase Live Data
        $liveData = new FirebaseController();
        $liveData->c2dLiveData([
            "number" => $first . $second,
            "buy" => $bidprice
        ]);
    }
}
