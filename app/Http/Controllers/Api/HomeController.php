<?php

namespace App\Http\Controllers\Api;

use App\Bwstaff;
use App\Location;
use App\Notification;
use Illuminate\Http\Request;
use Response;
use App\Device;

use Illuminate\Support\Facades\Input;

class HomeController extends \App\Http\Controllers\Controller
{

    public static function getDownDevicesCount(){
        $array = Device::where('ping', '!=', 1)->where('devicetype_id','!=','16')->get();
        $totaldowndevices = 0;
        foreach ($array as $row){
            if ($row->location->site_type != "fiz"){
                $totaldowndevices += 1;
            }
        }
        return $totaldowndevices;
    }

    public static function getTotalPppoe(){
        $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
        $totalhotspot = \DB::table('devices')->where('ping','!=','0')->sum('active_hotspot');
        return ($totalpppoe+$totalhotspot);
    }

    public function getProblemLocations(){
        $problemdevices =  \DB::select('SELECT COUNT(DISTINCT device_id) as problemdevices FROM faults');
        return $problemdevices['0']->problemdevices;
    }

    public function getDownPowerMons(){
        $powermonsdown = \DB::table('devices')->where('ping', '!=', 1)->where('devicetype_id','=','4')->count();

        return $powermonsdown;
    }

    public function getDashboardoutages(){

        $locations = Location::with('device')->where('site_type','!=','fiz')->get();
        $sounds = Notification::where('type','=','sound')->where('done','=','0')->get();
        foreach ($sounds as $sound){
            $sound->done = 1;
            $sound->save();
        }
        return view('left-panel',compact('locations','sounds'));
    }

}