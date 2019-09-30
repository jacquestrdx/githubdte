<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class PollSpesificDeviceByID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PollSpesificDeviceByID {device_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PollSpesificDeviceByID';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::updateDevice($this->argument('device_id'));
    }
}
