<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StartPingWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartPingWorker {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartPingWorker';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           PingWorker::StartPingWorker($this->argument('worker'));
    }
}
