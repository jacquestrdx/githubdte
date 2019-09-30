<?php

namespace App\Console\Commands;

use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class checkAllActivePPPOE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkAllActivePPPOE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if all pppoes are online';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Pppoeclient::checkOnlinePPPOES();
           // Device::pingall();
    }
}
