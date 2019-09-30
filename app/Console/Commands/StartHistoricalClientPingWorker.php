<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\HistoricalClientPingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StartHistoricalClientPingWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartHistoricalClientPingWorker {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartHistoricalClientPingWorker';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           HistoricalClientPingWorker::StartPingWorker($this->argument('worker'));
    }
}
