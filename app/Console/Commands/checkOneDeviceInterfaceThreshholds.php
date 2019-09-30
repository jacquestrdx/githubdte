<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Statable;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkOneDeviceInterfaceThreshholds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkOneDeviceInterfaceThreshholds {device_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkOneDeviceInterfaceThreshholds';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::checkOneDeviceInterfaceThreshholds($this->argument('device_id'));
           // Device::pingall();
    }
}
