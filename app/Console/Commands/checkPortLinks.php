<?php

namespace App\Console\Commands;

use App\DInterface;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\SlaReport;
use App\Tshwanereport;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkPortLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkPortLinks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkPortLinks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DInterface::checkPortLinks();
    }
}
