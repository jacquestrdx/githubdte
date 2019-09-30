<?php

namespace App\Console\Commands;

use App\SlaReport;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getIPNeighbors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getIPNeighbors {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'getIPNeighbors';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_start = microtime(true);
        Device::getAllIPNeigbors($this->argument('worker'));
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //\Log::info("GET IP NEIGHBORS TOOK $execution_time seconds");

    }
}
