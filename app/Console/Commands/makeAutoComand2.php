<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronJobController;
use Illuminate\Console\Command;
use DB;
class makeAutoComand2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeTest1:cron';

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
        $result = (new CronJobController)->dueBillCreate();

    }
}
