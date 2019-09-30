<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getAllActivePPPOE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAllActivePPPOE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update software on scheduled devices';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_start = microtime(true);
        Device::getAllPPPoe();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //\Log::info("getAllActivePPPOE TOOK $execution_time seconds");
    }
}
