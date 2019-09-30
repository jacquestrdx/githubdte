<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Location;
use App\Bwstaff;
use App\Device;

Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class ReportController extends Controller
{
    public static function generateReport(){	
    	$locations=Location::get();
    	
    	foreach ($locations as $location){
		    		foreach ($location->device as $device){
		    			$haspowermon = $device->devicetype->name;
                            if ($device->ping=="1"){
                                //echo "Name : ".$device->name."</br>";
                                //echo "Uptime : ".$device->uptime."</br>";
                            }else{
                                echo "Name : ".$device->name."</br>";
                                echo "Downtime : ".$device->uptime."</br>";
    				            }   
                            
    	                }
                    }
        }

        public function getLocationReportAJAX(){
            $locations = \DB::select('SELECT 
            locations.id,locations.name,count(devices.name) as devices, sum(devices.active_pppoe) as active_pppoe,sum(devices.active_stations) as active_stations,  sum(devices.active_hotspot) as active_hotspot
            FROM locations 
            inner join devices on locations.id = devices.location_id 
            group by locations.name  
            ORDER BY `active_hotspot`  DESC'
            );
            foreach ($locations as $location){
                if ($location->name  != "VIP CLIENTS"){
                    $locationbachaullink = "<a href='/backhaul/".$location->id."'>SHOW BACKHAULS</a>";
                    $array[] = [$location->id,$location->name,$location->devices,$location->active_pppoe,$location->active_stations,$location->active_hotspot,$locationbachaullink];
                }
            }
            return $array;
        }

        public function showLocationReport(){
            return view('report.location');
        }

}
