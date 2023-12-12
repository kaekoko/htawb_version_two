<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\CashIn;
use App\Models\CashOut;
use Illuminate\Support\Facades\Log;
class CashinoutDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:Cashinout';

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
        $date = Carbon::now()->subDays(30);
      
        CashIn::where('created_at', '<', $date)->delete();
        CashOut::where('created_at', '<', $date)->delete();
        Log::info('cash in cash out delete');
    }
}
