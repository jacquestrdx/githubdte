<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Statable;
use App\Device;
use App\Backhaul;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class findPossibleBackhauls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'findPossibleBackhauls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'findPossibleBackhauls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_start = microtime(true);
        Backhaul::findPossibleBackhauls();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //\Log::info("checkInterfaceThreshholds TOOK $execution_time seconds");
           // Device::pingall();
    }
}
