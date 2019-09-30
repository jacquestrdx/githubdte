<?php

namespace App\Console\Commands;

use App\Notification;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class sendHourlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendHourlyReport {interval}';

    /**
     * The console command description.
     *
     * @var string23
     */
    protected $description = 'sendHourlyReport';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Notification::sendHourlyEmailToUsers($this->argument('interval'));
           // Device::pingall();
    }
}
