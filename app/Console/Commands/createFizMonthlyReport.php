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


class createFizMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createFizMonthlyReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'createFizMonthlyReport';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Tshwanereport::generateMonthlyFizEmailReport();

           // Device::pingall();
    }
}
