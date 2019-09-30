<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class backupMikrotiks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupMikrotiks {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backupMikrotiks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::backupMikrotiks($this->argument('worker'));

        // Device::pingall();
    }
}
