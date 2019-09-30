<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;
use App\Jacques\InfluxLibrary;

use App\Notification;
use App\Jacques\DeviceNameFinder;

class Tshwanereport extends Model
{


    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

    public static function generateMonthlyFizEmailReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('-30 days');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time = $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::where('created_at', '<=', $formatted_time)->with('location')->with('notifications')->get();
        $pings = Tshwanereport::getPings($devices);
        foreach ($devices as $device) {
            echo "At least still doing something $device->name \n";

            if ( ($device->location->site_type == "fiz") and (($device->devicetype_id == "23") or ($device->devicetype_id == "24")) ) {
                $secondsdowntime = Notification::calculate30daysDowntime($device);
                if ($secondsdowntime == "0") {
                    if ($device->ping == "0") {
                        $secondsdowntime = $time;
                    } else {
                        $secondsdowntime = 0;
                    }
                }
                $uptime = round((100 - ($secondsdowntime / $time) * 100), 2);
                $finalresults[$device->location->name][] = [
                    "device" => $device->name,
                    "ip" => $device->ip,
                    "total-downtime" => $secondsdowntime,
                    "locationdescription" => $device->location->description,
                    "uptime" => $uptime
                ];
            }
        }


        $results = array();
        foreach ($finalresults as $locationname => $location) {
            $sum =0;
            $sumdowntime =0;
            $count = 0;
            $temp = 0;

            foreach ($location as $device) {
                $count++;
                if ($device['uptime']=="100"){
                    $temp = "100";
                }
                $sum += $device['uptime'];
            }
            if ($count == sizeof($location)){
                $results[$locationname]['uptime'] = $sum/$count;
                $results[$locationname]['description'] = $device['locationdescription'];
            }
            if ($temp =="100") {
                $results[$locationname]['uptime'] = $sum/$count;
                $results[$locationname]['description'] = $device['locationdescription'];
            }

        }
//
        $tempresults = array();

        foreach ($results as $key => $row){
            $tempresults[] = [$key,$row['uptime'],$row['description']];
        }
        $tempdeviceresults = array();
        foreach ($finalresults as $key => $row){
            foreach($row as $col){
                echo "At least still doing something \n";
                $tempdeviceresults[] = array(
                    'device' => $col['device'],
                    'ip' => $col['ip'],
                    'total_downtime' =>$col['total-downtime'],
                    'uptime' => $col['uptime']
                );
            }
        }

        $report = new Tshwanereport();
        $report->fiz_table = json_encode($tempresults);
        $report->device_table = json_encode($tempdeviceresults);
        $report->latency_table = json_encode($pings);
        $report->type ="monthly";
        $report->comment = "";
        $report->comment2 = "";
        $report->save();

    }

    public static function generateWeeklyFizEmailReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('-7 days');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time = $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::where('created_at', '<=', $formatted_time)->with('location')->with('notifications')->get();
        $pings = Tshwanereport::getPings($devices);
        foreach ($devices as $device) {
            echo "At least still doing something $device->name \n";

            if ( ($device->location->site_type == "fiz") and (($device->devicetype_id == "23") or ($device->devicetype_id == "24") or ($device->devicetype_id == "3")) ) {
                $secondsdowntime = Notification::calculateWeeklyDowntime($device);
                if ($secondsdowntime == "0") {
                    if ($device->ping == "0") {
                        $secondsdowntime = $time;
                    } else {
                        $secondsdowntime = 0;
                    }
                }
                $uptime = round((100 - ($secondsdowntime / $time) * 100), 2);
                $finalresults[$device->location->name][] = [
                    "device" => $device->name,
                    "ip" => $device->ip,
                    "total-downtime" => $secondsdowntime,
                    "locationdescription" => $device->location->description,
                    "uptime" => $uptime
                ];
            }
        }


        $results = array();
        foreach ($finalresults as $locationname => $location) {
            $sum =0;
            $sumdowntime =0;
            $count = 0;
            $temp = 0;

            foreach ($location as $device) {
                $count++;
                if ($device['uptime']=="100"){
                    $temp = "100";
                }
                $sum += $device['uptime'];
            }
            if ($count == sizeof($location)){
                $results[$locationname]['uptime'] = $sum/$count;
                $results[$locationname]['description'] = $device['locationdescription'];
            }
            if ($temp =="100") {
                $results[$locationname]['uptime'] = $sum/$count;
                $results[$locationname]['description'] = $device['locationdescription'];
            }

        }
//
        $tempresults = array();
        foreach ($results as $key => $row){
            $tempresults[] = [$key,$row['uptime'],$row['description']];
        }
        $tempdeviceresults = array();
        foreach ($finalresults as $key => $row){
            foreach($row as $col){
                echo "At least still doing something \n";
                $tempdeviceresults[] = array(
                    'device' => $col['device'],
                    'ip' => $col['ip'],
                    'total_downtime' =>$col['total-downtime'],
                    'uptime' => $col['uptime']
                );
            }
        }

        $report = new Tshwanereport();
        $report->fiz_table = json_encode($tempresults);
        $report->device_table = json_encode($tempdeviceresults);
        $report->latency_table = json_encode($pings);
        $report->comment = "";
        $report->type ="monthly";
        $report->comment2 = "";
        $report->save();

    }

    public static function getPings($devices){
        $pingresults = array();
        foreach ($devices as $device) {
            if (($device->location->site_type == "fiz") and (($device->devicetype_id == "23") or ($device->devicetype_id == "24"))) {
                echo $device->name . " started \n";
                $influx = new InfluxLibrary();
                $query = "SELECT * FROM pings where host ='" . $device->ip . "' order by time desc limit 150 ";
                $stats = $influx->selectFromDb($query);
                if (isset($stats)) {
                    $pingcount = 0;
                    $array = array();
                    foreach ($stats as $stat) {
                        if($stat->value !="-1"){
                            $pingcount++;
                            $array[] =  $stat->value;
                        }
                    }
                    if(count($array)=="0") {
                    }else{
                        $pingresults[] = [$device->name,$device->location->description,round(array_sum($array) / count($array),2)];
                    }
                }
                echo $device->name .  "\n";

            }
        }
        return $pingresults;
    }



}

