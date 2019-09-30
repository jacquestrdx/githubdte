<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class getMikrotikRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getMikrotikRoutes';

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
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $device->getMikrotikDefaultGateway();
        }
            Device::getAllMikrotikRoutes();

           // Device::pingall();
    }
}
