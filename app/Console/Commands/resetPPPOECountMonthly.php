<?php

namespace App\Console\Commands;

use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class resetPPPOECountMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetPPPOECountMonthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'resetPPPOECountMonthly';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::resetPPPOECountMonthly();

        // Device::pingall();
    }
}
