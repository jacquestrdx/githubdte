<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\SlaReport;
use App\Tshwanereport;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class createFizWeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createFizWeeklyReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'createFizWeeklyReport';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Tshwanereport::generateWeeklyFizEmailReport();

           // Device::pingall();
    }
}
