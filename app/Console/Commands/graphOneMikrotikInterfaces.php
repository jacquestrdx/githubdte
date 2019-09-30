<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class graphOneMikrotikInterfaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graphOneMikrotikInterfaces {device_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll all Mikrotiks for interfaces';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::graphInterfacesByID($this->argument('device_id'));
        // Device::pingall();
    }
}
