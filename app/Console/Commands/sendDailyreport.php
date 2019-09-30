<?php

namespace App\Console\Commands;

use App\SlaReport;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class sendDailyreport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendDailyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sendDailyreport';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            SlaReport::sendDailyreport();
    }
}
