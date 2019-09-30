<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class PollMikrotikSectors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PollMikrotikSectors  {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PollUBNTSectors';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Device::PollMikrotikSectors($this->argument('worker'));
        // Device::pingall();
    }
}
