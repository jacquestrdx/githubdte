<?php

namespace App\Console\Commands;

use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Fault;
use App\Client;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class graphAllClientPPPOES extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graphAllClientPPPOES';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'graphAllClientPPPOES';

    /** 
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Client::graphAllClientPPPOES();
           // Device::pingall();
    }
}
