<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\SlaReport;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class create30daysICMPSLAReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create30daysICMPSLAReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create 7 Day ICMP report for all devices';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        SlaReport::generateDevice30daysICMPReport();
    }
}
