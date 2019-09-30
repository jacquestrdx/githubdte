<?php

namespace App\Console\Commands;

use App\DInterface;
use App\UserNotification;
use App\Warning;
use App\Statable;
use App\Pppoeclient;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Device;
use App\Client;
use App\Fault;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceUpdateController;
use App\Http\Controllers\DevicetypeController;


class ResetDownsToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ResetDownsToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Downs Today';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo " Reset Downs Today \n";
        Device::ResetDownsToday();
        echo " Reset Thresholds\n";
        DInterface::ResetThresholdsToday();
        echo " Reset Faults \n";
        Fault::ResetFaults();
        echo " ResetRemove Warnings \n";
        Warning::removeAll();
        echo " Reset Clients Downs Today \n";
        Client::resetdownstoday();
        echo " Reset old user notifications\n";
        UserNotification::deleteOld();
        $deleted = \DB::delete('delete from pppoeclients where pppoeclients.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from statables where statables.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from ips where ips.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from interfaces where interfaces.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from backhauls where backhauls.location_id not in (select id from locations)');
        $deleted = \DB::delete('delete from backhauls where backhauls.to_location_id not in (select id from locations)');
        $deleted = \DB::delete('delete from backhauls where backhauls.dinterface_id not in (select id from interfaces)');
        $deleted = \DB::delete('delete from possible_backhauls where possible_backhauls.from_device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from possible_backhauls where possible_backhauls.to_device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from possible_backhauls where possible_backhauls.from_location not in (select id from locations)');
        $deleted = \DB::delete('delete from possible_backhauls where possible_backhauls.to_location not in (select id from locations)');
        $deleted = \DB::delete('delete from neighbors where neighbors.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from interfaces where interfaces.device_id not in (select id from devices)');
        $deleted = \DB::delete('delete from interface_warnings');
        $deleted = \DB::delete('delete from usernotifications');

        $sectorids = ["2","22","15","17"];
        $devices = Device::wherein('devicetype_id',$sectorids)->get();

        foreach($devices as $device) {
            $statables = Statable::where('device_id', $device->id)->lists('id');
            $pppoes = Pppoeclient::with('device')->with('statable')->wherein('statable_id', $statables)->get();
            $downsum = 0;
            $upsum = 0;
            foreach ($pppoes as $pppoe) {
                if ($pppoe->statable->device_id == $device->id) {
                    $downsum += round(($pppoe->download_speed / 1024 / 1024),2);
                    $upsum += round(($pppoe->upload_speed / 1024 / 1024),2);
                }
            }
            $device->comment = " " . round($downsum,2) . " Mbps / " . round($upsum,2) . " Mbps";
            echo ($device->comment)." \n";
            $device->save();
        }
    }
}
