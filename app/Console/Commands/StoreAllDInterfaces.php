<?php

namespace App\Console\Commands;

use App\Jacques\MikrotikLibrary;
use App\SlaReport;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StoreAllDInterfaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StoreAllDInterfaces {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StoreAllDInterfaces';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::StoreAllDInterfaces($this->argument('worker'));
    }
}
