<?php

namespace App\Jacques;
use App\Device;

class DeviceNameFinder{

    public function getDeviceNameFromID($id){
            $device3 = Device::find($id);
            if (isset($device3->name)){
            return $device3->name;
            }else{
                return "Device name not found";
            }
    }



    public function getDeviceIDFromName($name){
        $device = Device::where('name',$name)->first();
        if (isset($device->name)){
            return $device->id;
        }else {
            return "";
        }
    }

    public function getDeviceFromIP($ip){
        $device = Device::where('ip',$ip)->get();
        dd($device);
    }
}