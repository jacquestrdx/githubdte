<?php

namespace App\Console\Commands;

use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class findPeakInterfaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'findPeakInterfaces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Peak Intervals for interfaces';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        MikrotikLibrary::findPeakInterfaces();
        // Device::pingall();
    }
}
