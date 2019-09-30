<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class ImportDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportDevices  {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Devices';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::importDevices($this->argument('file'));

           // Device::pingall();
    }
}
