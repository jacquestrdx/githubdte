<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Location;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class FindDeviceLocationByDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FindDeviceLocationByDescription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'FindDeviceLocationByDescription';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            $devices = Device::get();
            foreach ($devices as $device){
                $location = Location::where('name',$device->location_description)->first();
                if (isset($location->id)){
                    echo "FOUND ONE"."\n";
                    $locationid = $location->id;
                    $device->location_id = $locationid;
                    $device->save();
                }
            }
           // Device::pingall();
    }
}
