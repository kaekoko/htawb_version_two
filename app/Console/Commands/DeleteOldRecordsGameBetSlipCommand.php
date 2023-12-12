<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CashIn;
use App\Models\CashOut;
use Illuminate\Console\Command;
use App\Models\BetslipTransaction;
use Illuminate\Support\Facades\Log;

class DeleteOldRecordsGameBetSlipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:delete';

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
        $date = Carbon::now()->subDays(2);
        BetslipTransaction::where('created_at', '<', $date)->delete();
        Log::info('Game Bet and cash in cash out delete Slip deleted');
    }
}
