<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\TempBestslipTransaction;
use Illuminate\Support\Facades\Log;

class TempSLipDeleteLastHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:lasthourdata';
    protected $description = 'Delete data from the last hour';


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
//         $onehourago = Carbon::now()->subMinutes( 50 );
       
//         //$data = TempBestslipTransaction::where('created_at', '<', $onehourago)->delete();
//         $data = TempBestslipTransaction::where('created_at', '<', $onehourago)->where('provider_name','!=','SBO')->where('provider_name','!=','UG Sport')->delete();
// //         $data=TempBestslipTransaction::query()
// //          ->where('created_at', '<', $onehourago)->where('provider_name','!=','SBO')->where('provider_name','!=','UG Sport')
// //          ->each(function ($oldRecord) {
// //           $newRecord = $oldRecord->replicate();
// //           $newRecord->setTable('betslip_transactions');
// //           $newRecord->save();
// //           $oldRecord->delete();
// //   });
      
//         // Process $data as needed
//         Log::info('temp betslip delete after');
//        Log::info($data);
//        $this->info('Data from the last hour has been deleted.');
    }
}
