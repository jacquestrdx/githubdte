<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class PollSpesificDeviceType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PollSpesificDeviceType {devicetype}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Specific device type';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           Device::PollSpesificDeviceTypes($this->argument('devicetype'));
    }
}
