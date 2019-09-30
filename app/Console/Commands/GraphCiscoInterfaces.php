<?php

namespace App\Console\Commands;

use App\ClientPingWorker;
use App\Jacques\CiscoLibrary;
use App\Jacques\MikrotikLibrary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class GraphCiscoInterfaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GraphAllMikrotikInterfaces {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GraphAllMikrotikInterfaces';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           CiscoLibrary::CalculateThroughput($this->argument('worker'));
    }
}
