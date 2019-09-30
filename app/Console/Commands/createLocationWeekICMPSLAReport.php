<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\SlaReport;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class createLocationWeekICMPSLAReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createLocationWeekICMPSLAReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Weekly ICMP report for all devices per location';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        SlaReport::generateLocationWeekICMPReport();
    }
}
