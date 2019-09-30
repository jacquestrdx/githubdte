<?php

namespace App\Http\Controllers;

use App\Backhaul;
use App\Location;
use App\Pppoeclient;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Device;
use App\SlaReport;

class SlaReportController extends Controller
{

    public function showDeviceReportMonth(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/devicemonthreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }

    public function showTopTwenty(){
        $month = date("Y-m-01");
        $instancebackhaul = Backhaul::first();
        $instancelocation = Location::first();
        $backhauls = \DB::SELECT('select 
        locations.name as locationname,
        locations.id as locationid,
        backhauls.to_location_id,
        interfaces.maxtxspeed,
        interfaces.maxrxspeed,
        backhaultypes.name,
        interfaces.threshhold
         from backhauls  
        inner join interfaces on interfaces.id = backhauls.dinterface_id
        inner join locations on backhauls.location_id = locations.id 
        inner join backhaultypes on backhaultypes.id = backhauls.backhaultype_id
        order by interfaces.rxspeed DESC limit 20');
        $devices = Device::whereIn('devicetype_id', ["2", "15", "17","22"])->orderby('active_stations','DESC')->with('location')->take('20')->get();
        $lowsectors = Device::whereIn('devicetype_id', ["2", "15", "17","22"])->orderby('active_stations','ASC')->with('location')->take('75')->get();
        $locations = \DB::SELECT('
        select locations.id,locations.name,sum(devices.active_pppoe) as active_pppoe,
        sum(devices.active_stations) as active_stations from locations 
        inner join devices on devices.location_id = locations.id
        group by locations.id 
        order by active_pppoe DESC limit 20');
        $pppoeclients = \DB::select('SELECT count(distinct(pppoeclients.id)) as newpppoeclients,locations.name FROM pppoeclients 
        inner join devices on pppoeclients.device_id = devices.id
        inner join locations on devices.location_id = locations.id
        where pppoeclients.created_at > "'.$month.'" group by locations.name  ORDER BY `newpppoeclients`  DESC
');

        return view('slareport.toptwenty',compact('lowsectors','backhauls','instancebackhaul','devices','instancelocation','month','locations','pppoeclients'));
    }

    public function showTopTwentyUBNT(){
        $devices = Device::whereIn('devicetype_id', ["2","22"])->orderby('active_stations','DESC')->with('location')->take('40')->get();
        $type = "UBNT";
        return view('slareport.toptwentysectors',compact('backhauls','instancebackhaul','devices','type'));
    }

    public function showTopTwentyCAMBIUM(){
        $devices = Device::whereIn('devicetype_id', ["17"])->orderby('active_stations','DESC')->with('location')->take('40')->get();
        $type = "UBNT";
        return view('slareport.toptwentysectors',compact('backhauls','instancebackhaul','devices','type'));
    }



    public function showSectors(){
        $devices = Device::whereIn('devicetype_id', ["17","2","22","15","10","11"])->orderby('active_stations','DESC')->with('location')->get();
        $type = "ALL";
        return view('slareport.toptwentysectors',compact('backhauls','instancebackhaul','devices','type'));
    }


    public function showDeviceReportWeek(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/deviceweekreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }

    public function showDeviceReportDay(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/devicedayreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }

    public function showDeviceReport24h(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/device24hreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }

    public function showDeviceReport30days(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/device30daysreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }



    public function showDeviceReport7days(){
        $device = Device::find(1);
        date_default_timezone_set('Africa/Johannesburg');
        $filename = '/var/www/html/dte/storage/reports/device7daysreport.csv';
        $slareport = array_map('str_getcsv', file($filename));
        $filetime = filemtime($filename);
        $filetime = strtotime('+2 hours', $filetime);

        return view('slareport.devicesindex',compact('slareport','device','filetime'));
    }


    public static function secondsToTime($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }


}
