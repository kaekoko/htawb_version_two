<?php

namespace App\Console;

use App\Models\Section;
use App\Invoker\invoke1D;
use App\Invoker\invoke2D;
use App\Invoker\invoke3D;
use App\Invoker\invokeAll;
use App\Invoker\invokeC1D;
use App\Invoker\invokeC2D;
use Spatie\ShortSchedule\ShortSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\LuckyNumberCreateCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('number:reset')->dailyAt('00:01')->timezone('Asia/Yangon');
        $schedule->command('delete:Cashinout')->daily();
        //lucky number 2D
        invoke2D::luckyNumberSection2dCron($schedule);
        // 2d daily static
        invoke2D::dailystaticCron($schedule);

        //lucky number 2D
        invoke1D::luckyNumberSection1dCron($schedule);
        // 2d daily static
        invoke1D::dailystaticCron($schedule);

        //lucky number Crypto 2D
        invokeC2D::luckyNumberSectionC2DCron($schedule);
        // Crypto 2D Daily Static
        invokeC2D::dailystaticCron($schedule);

        invokeC1D::luckyNumberSectionC1DCron($schedule);
        // Crypto 1D Daily Static
        invokeC1D::dailystaticCron($schedule);

        //lucky number 3D
        invoke3D::luckyNumberSection3dCron($schedule);

        //delete game record
        $schedule->command('records:delete')->daily();

        //Test schedule run
        // $schedule->command('test:cron')->everyMinute()->timezone('Asia/Yangon');

        //$schedule->command('bm:cron')->everyThreeMinutes()->timezone('Asia/Yangon');
    }

    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {
        // No need to run because crypto-live-2d data fetch from lucky-8 firebase.
        // $shortSchedule->command('bm:cron')->everySeconds(2)->withoutOverlapping(10);
        // $shortSchedule->command('slip:cron')->everySeconds(3)->withoutOverlapping(10);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}