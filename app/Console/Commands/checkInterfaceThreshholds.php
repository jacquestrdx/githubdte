<?php

namespace App\Console\Commands;

use App\InterfaceWarning;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Statable;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkInterfaceThreshholds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkInterfaceThreshholds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkInterfaceThreshholds';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_start = microtime(true);
        Device::checkInterfaceThreshholds();
        Device::updateInterfaces();
        Device::syncInterfaces();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //\Log::info("checkInterfaceThreshholds TOOK $execution_time seconds");
        //InterfaceWarning::pushAll();
           // Device::pingall();
    }
}
