<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getAllMikrotikIPs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAllMikrotikIPs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'getAllMikrotikIPs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time_start = microtime(true);
        Device::getAllMikrotikIPs();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        //\Log::info("getAllMikrotikIPs TOOK $execution_time seconds");
    }
}
