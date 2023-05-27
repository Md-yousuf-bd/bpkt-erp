<?php

namespace App\Console;

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
        \App\Console\Commands\makeAutoComand::class,
        \App\Console\Commands\makeAutoComand2::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->command('makeTest:cron')->timezone('Asia/Dhaka')->dailyAt('02:05');

        $file = 'command1_output.log';
         $schedule->command('makeTest1:cron')->timezone('Asia/Dhaka')->dailyAt('01:05')->sendOutputTo($file);;
//         $schedule->command('makeTest1:cron')->timezone('Asia/Dhaka')->dailyAt('19:05');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
