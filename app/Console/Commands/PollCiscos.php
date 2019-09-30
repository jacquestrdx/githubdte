<?php

namespace App\Console\Commands;

use App\ClientPingWorker;
use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class PollCiscos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PollCiscos {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PollCiscos';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           Device::PollCiscos($this->argument('worker'));
    }
}
