<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBackups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkBackups';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Device::checkBackups();

           // Device::pingall();
    }
}
