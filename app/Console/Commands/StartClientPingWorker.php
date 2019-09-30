<?php

namespace App\Console\Commands;

use App\ClientPingWorker;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StartClientPingWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartClientPingWorker {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartClientPingWorker';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           ClientPingWorker::StartPingWorker($this->argument('worker'));
    }
}
