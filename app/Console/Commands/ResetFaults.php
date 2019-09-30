<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Fault;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class ResetFaults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ResetFaults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Faults';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            //Device::updateall();
           Fault::ResetFaults();
    }
}
