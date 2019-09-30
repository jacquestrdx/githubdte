<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class findFaults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'findFaults';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find Faulty devices';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Fault::getFaultyDevices();

           // Device::pingall();
    }
}
