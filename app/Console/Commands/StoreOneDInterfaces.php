<?php

namespace App\Console\Commands;

use App\SlaReport;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StoreOneDInterfaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StoreOneDInterfaces {device_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StoreOneDInterfaces';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::StoreOneDInterfaces($this->argument('device_id'));
    }
}
