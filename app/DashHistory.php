<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashHistory extends Model
{
    public static function addEntry()
    {
        $dashhistory = new DashHistory();
        $dashhistory->active_pppoe = DashHistory::getTotalPppoe();
        $dashhistory->max_pppoe = DashHistory::getMaxPppoe();
        $dashhistory->down_devices = DashHistory::getDownDevicesCount();
        $dashhistory->problem_devices = DashHistory::getProblemDevices();
        $dashhistory->power_monitors_down = DashHistory::getDownPowerMons();
        $dashhistory->save();
    }

    public static function getDownDevicesCount(){
        $totaldowndevices = \DB::table('devices')->where('ping', '!=', 1)->where('devicetype_id','!=','16')->count();
        return $totaldowndevices;
    }

    public static function getTotalPppoe()
    {
        if(config('dashboard.pppoe')=="1"){
            $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
            $totalhotspot = \DB::table('devices')->where('ping','!=','0')->sum('active_hotspot');
            return ($totalpppoe+$totalhotspot);
        }else{
            $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
            return ($totalpppoe);
        }
    }

    public static function getMaxPppoe()
    {
        $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
        $totalhotspot = \DB::table('devices')->where('ping','!=','0')->sum('active_hotspot');

        return ($totalpppoe+$totalhotspot);
    }

    public static function getProblemDevices()
    {
        $problemdevices =  \DB::select('SELECT COUNT(DISTINCT device_id) as problemdevices FROM faults where faults.acknowledged != "1"');
        return $problemdevices['0']->problemdevices;
    }

    public static function getDownPowerMons()
    {
        $powermonsdown = \DB::table('devices')->where('ping', '!=', 1)->where('devicetype_id', '=', '4')->count();

        return $powermonsdown;
    }
}
