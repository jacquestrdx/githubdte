<?php

namespace App\Console\Commands;

use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getAllMikrotikInterfaceNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAllMikrotikInterfaceNames';

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
        //MikrotikLibrary::getMikrotikInterfaceNames();
        // Device::pingall();
    }
}
