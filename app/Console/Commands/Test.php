<?php

namespace App\Console\Commands;

use App\Backhaul;
use App\Client;
use App\Notification;
use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;
use App\Jacques\MikrotikLibrary;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test {worker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll all Mikrotiks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    Device::FixHackedMT(($this->argument('worker')));
    }
}
