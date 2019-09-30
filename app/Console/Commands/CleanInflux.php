<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class CleanInflux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CleanInflux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CleanInflux';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::CleanInflux();
           // Device::pingall();
    }
}
