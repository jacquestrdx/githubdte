<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\PingWorker;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class StartPingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartPingJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'StartPingJob';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           PingWorker::GeneratePingFiles();
    }
}
