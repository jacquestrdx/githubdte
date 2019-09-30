<?php

namespace App\Http\Controllers;

use App\Backhaul;
use App\Blackboardalert;
use App\BGPPeer;
use App\DInterface;
use App\Fault;
use App\Interfacelog;
use App\InterfaceWarning;
use App\Jacques\InfluxLibrary;
use App\Queuestats;
use App\Http\Requests;

use App\Pppoeclient;
use Illuminate\Http\Request;
use App\Device;
use App\Location;
use App\Notification;

use App\DeviceController;
use Khill\Lavacharts\Lavacharts;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showDashboard()
    {

        $locations = Location::where('site_type','!=','fiz')->with('device')->get();
        $bgppeers = BGPPeer::get();
        $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
        $totaldownlocationslist= Location::where('status', '<', 0)->get();
        $devices = Device::where("devicetype_id","=","1")->get();
        $instantdevice = Device::first();

        //return view('home',compact('totalpppoe','gaugechart','totaldownlocationslist','locations'));
        return view('home',compact('sounds','totalpppoe','gaugechart','totaldownlocationslist','locations','devices','bgppeers','instantdevice'));
    }

    public function index()
    {
        $bgppeers = BGPPeer::get();
        $locations = Location::with('device')->where('site_type','!=','fiz')->get();
        $date = new \DateTime;
        $instantdevice = Device::first();
        $date->modify('-30 minutes');
        $devices = Device::get();
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications = Notification::where('updated_at','>',$formatted_date)->orderby('updated_at','desc')->get();
        $sounds = Notification::where('type','=','sound')->where('done','!=','1')->get();
        foreach ($sounds as $sound){
            $sound->done = "1";
            $sound->save();
        }
        //return view('home',compact('totalpppoe','gaugechart','totaldownlocationslist','locations'));
        return view('home',compact('sounds','totalpppoe','gaugechart','totaldownlocationslist','locations','notifications','devices','bgppeers','instantdevice'));
    }

    public function getSystemLoadAJAX(){
        $load = sys_getloadavg();
        if (array_key_exists('1',$load)){
            return $load['1'];
        }else{
            return "";
        }
    }

    public function getActiveHotspotUsersAJAX(){
        $hotspots = Device::sum('active_hotspot');
        return $hotspots;
    }
    public function getActiveHotspotRoutersAJAX(){
        $hotspots = Device::where('max_active_hotspot','>','0')->where('ping','1')->count();
        return $hotspots;
    }

    public function getMaxHotspotRoutersAJAX(){
        $hotspots = Device::where('max_active_hotspot','>','0')->count();
        return $hotspots;
    }

    public function getMaxHotspotUsersAJAX(){
        $hotspots = Device::sum('max_active_hotspot');
        return $hotspots;
    }


    public function newdashboard(){
        $bgppeers = BGPPeer::get();
        $locations = Location::with('device')->where('site_type','!=','fiz')->get();
        $date = new \DateTime;
        $backhauls = \DB::SELECT('select 
locations.name as locationname,
backhauls.to_location_id,
interfaces.txspeed,
interfaces.rxspeed,
interfaces.updated_at,
interfaces.maxtxspeed,
interfaces.maxrxspeed,
backhaultypes.name,
interfaces.threshhold 
 from backhauls  
inner join interfaces on interfaces.id = backhauls.dinterface_id
inner join locations on backhauls.location_id = locations.id 
inner join backhaultypes on backhaultypes.id = backhauls.backhaultype_id 
order by interfaces.rxspeed DESC limit 10');
        $instantdevice = Device::first();
        $power_devices  = Device::where('voltage_monitor','1')->get();
        $date->modify('-30 minutes');
        $devices = Device::get();
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications = Notification::where('updated_at','>',$formatted_date)->orderby('updated_at','desc')->get();
        $sounds = Notification::where('type','=','sound')->where('done','!=','1')->get();
        foreach ($sounds as $sound){
            $sound->done = "1";
            $sound->save();
        }
        $instancebackhaul = Backhaul::first();


        //return view('home',compact('totalpppoe','gaugechart','totaldownlocationslist','locations'));
        return view('dashboard.index',compact('power_devices','sounds','instancebackhaul','backhauls','totalpppoe','gaugechart','totaldownlocationslist','locations','notifications','devices','bgppeers','instantdevice'));

    }

    public function showOuttageDashboard(){
        $locations = Location::with('device')->where('site_type','!=','fiz')->get();
        return view('dashboard.outages',compact('locations'));
    }

    public function showPowerDashboard(){
        $locations = Location::with('device')->where('site_type','!=','fiz')->get();

        $power_devices  = Device::where('voltage_monitor','1')->get();
        return view('dashboard.power',compact('locations','power_devices'));
    }

    public function showBackhaulDashboard(){

        $backhauls = \DB::SELECT('select 
locations.name as locationname,
backhauls.to_location_id,
interfaces.txspeed,
interfaces.rxspeed,
interfaces.updated_at,
interfaces.maxtxspeed,
interfaces.maxrxspeed,
backhaultypes.name,
interfaces.threshhold 
 from backhauls  
inner join interfaces on interfaces.id = backhauls.dinterface_id
inner join locations on backhauls.location_id = locations.id 
inner join backhaultypes on backhaultypes.id = backhauls.backhaultype_id 
order by interfaces.rxspeed DESC limit 10');


        $instancebackhaul = Backhaul::first();
        return view ('dashboard.backhauls',compact('backhauls','instancebackhaul'));
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
    public function showDashboardoutages(){

        $locations = Location::with('device')->where('site_type','!=','fiz')->get();
        $powermonitors = Device::where('devicetype_id','4')->get();
        $sounds = Notification::where('type','=','sound')->where('done','=','0')->get();
        foreach ($sounds as $sound){
            $sound->done = 1;
            $sound->save();
        }
        return view('layouts.left-panel-new',compact('locations','sounds','powermonitors'));
    }

    public function showInterfaceDashboard(){
        return view('dashboard.interfaces');
    }

    public function getDownFizzes(){

        $locations = Location::with('device')->where('site_type','fiz')->get();

        return view('fiz-panel',compact('locations'));
    }

    public function getOnlineFizzes(){
        return $locations = Location::with('device')->where('site_type','fiz')->where('status','0')->count();
    }

    public function getOfflineFizzes(){
        $count = 0;
        $locations = Location::with('device')->where('site_type','fiz')->get();
        foreach ($locations as $location){
            $counter = 0;
            foreach($location->device as $device){
                if($device->ping == 0){
                    $counter += 1;
                }
            }
            if ( ($counter == sizeof($location->device) and ($counter > 0))){
                $count +=1;
            }
        }
        return $count;
    }

    public function getPartOfflineFizzes(){
        $count = 0;
        $locations = Location::with('device')->where('site_type','fiz')->get();
        foreach ($locations as $location){
            $counter = 0;
            foreach($location->device as $device){
                if($device->ping == 0){
                    $counter += 1;
                }
            }
            if ( ($counter != sizeof($location->device) and ($counter > 0))){
                $count +=1;
            }
        }
        return $count;
    }

    public function getQueueStats(){
        return Queuestats::PollGenericStats();
    }

    public function showQueues(){
        $stats = Queuestats::PollGenericStats();
        foreach ($stats as $stat){
            $queues[] = $stat['0'];
        }
        return view('sipextention.queues',compact('queues'));
    }

    public function getOnlineFizUsers(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://services.projectisizwe.org:8001/active',
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        $resp = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($resp);
       return $response->item;
    }

    public function showFizDashboard(){

        return view('layouts.fizdashboard');

    }

    public static function getDownDevicesCount(){
        $array = Device::where('ping', '!=', 1)->get();
        $totaldowndevices = 0;
            foreach ($array as $row){
                if ($row->location->site_type != "fiz"){
                    $totaldowndevices += 1;
                }
            }
        return $totaldowndevices;
    }

    public static function getDownFizDevicesCount(){
        $array = Device::where('ping', '!=', 1)->where('devicetype_id','!=','16')->get();
        $totaldowndevices = 0;
        foreach ($array as $row){
            if ($row->location->site_type == "fiz"){
                $totaldowndevices += 1;
            }
        }
        return $totaldowndevices;
    }


    public static function getTotalPppoe(){
        $devices = Device::get();
        if(config('dashboard.pppoe')=="1"){
            $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
            $totalhotspot = \DB::table('devices')->where('ping','!=','0')->sum('active_hotspot');
            return ($totalpppoe+$totalhotspot);
        }else{
            $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
            return ($totalpppoe);
        }

    }

    public static function getMaxPppoe(){
        $devices = \DB::select('select sum(maxactivepppoe) as maxpppoe from devices');
        return $devices['0']->maxpppoe;
    }




    public static function getProblemLocations(){
        $problemdevices =  \DB::select('SELECT COUNT(DISTINCT device_id) as problemdevices FROM faults where faults.status != "0"');
        return $problemdevices['0']->problemdevices;
    }

    public static function getDownPowerMons(){
        $powermonsdown = \DB::table('devices')->where('ping', '!=', 1)->where('devicetype_id','=','4')->count();

        return $powermonsdown;
    }

    public function showBlackBoard(){
        $interfacelogs = array();
        $interfacewarnings = array();
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d 00:00:00');
        $one_hour_ago = new \DateTime;
        $one_hour_ago->modify("-1hour");
        $one_hour_ago_formatted = $one_hour_ago->format('Y-m-d H:i:s');
        $yesterday = new \DateTime;
        $yesterday->modify("-1 day");
        $yesterday_formatted = $yesterday->format('Y-m-d 00:00:00');
        $bgppeers = BGPPeer::where('state','!=','established')->where('disabled','false')->get();
        $devices = Device::where('ping','0')->where('devicetype_id','!=',"4")->get();
        $up_devices = Device::where('ping','1')->where('lastdown','>',$one_hour_ago_formatted)->get();
        $devices_ids = Device::where('include_interfaces','1')->lists('id');
        $powermonitors = Device::where('ping','0')->where('devicetype_id',"4")->get();
        $interfacewarnings_objects = InterfaceWarning::with('dinterface')->where('ack','0')->where('created_at','>',$yesterday_formatted)->orderby('created_at','DESC')->get();
        $interfacelogs_objects = Interfacelog::where('created_at','>',$formatted_date)->whereIn('device_id',$devices_ids)->orderBy('created_at','DESC')->get();

        foreach($interfacelogs_objects as $interfacelog){
            $interfacelogs[$interfacelog->dinterface_id][] =  [$interfacelog->dinterface->name,$interfacelog->dinterface_id];
        }
        foreach($interfacewarnings_objects as $interfacewarnings_object){
            $interfacewarnings[$interfacewarnings_object->dinterface_id][] = [
                    $interfacewarnings_object->dinterface->name,
                    $interfacewarnings_object->dinterface->threshhold,
                    $interfacewarnings_object->threshold,
            ];
        }

        $instantinterface = DInterface::first();
        $faults = Fault::where('created_at','>',$formatted_date)->where('acknowledged','0')->get();
        return view('dashboard.blackboard',compact('instantinterface','one_hour_ago_formatted','bgppeers','devices','interfacewarnings','interfacelogs','faults','powermonitors','up_devices'));
    }

    public function showBlackboardOutages(){
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d 00:00:00');
        $one_hour_ago = new \DateTime;
        $one_hour_ago->modify("-1hour");
        $one_hour_ago_formatted = $one_hour_ago->format('Y-m-d H:i:s');
        $yesterday = new \DateTime;
        $yesterday->modify("-1 day");
        $yesterday_formatted = $yesterday->format('Y-m-d 00:00:00');
        $bgppeers = BGPPeer::where('state','!=','established')->where('disabled','false')->get();
        $devices = Device::where('ping','0')->where('devicetype_id','!=',"4")->get();
        $powermonitors = Device::where('ping','0')->where('devicetype_id',"4")->get();
        $interfacewarnings = InterfaceWarning::with('dinterface')->where('ack','0')->groupby('dinterface_id')->where('updated_at','>',$yesterday_formatted)->orderby('created_at','DESC')->get();
        $interfacelogs = Interfacelog::where('created_at','>',$one_hour_ago_formatted)->get();
        $faults = Fault::where('acknowledged','0')->get();
        return view('dashboard.blackboardoutages',compact('one_hour_ago_formatted','bgppeers','devices','interfacewarnings','interfacelogs','faults','powermonitors'));
    }




    public function getDownBGP(){
        $instantdevice = Device::first();
        $bgppeers = BGPPeer::get();
	    $devices = Device::get();
        return view('layouts.bottom-panel',compact('instantdevice','bgppeers','devices'));
    }


    public function getWhatsappReport(){
        $locations = Location::with('device')->get();
        $totaldowndevices = HomeController::getDownDevicesCount();
        $totalpppoe =  HomeController::getTotalPppoe();
        $powermonsdown = HomeController::getDownPowerMons();
        return view('left-panel-whatsapp',compact('locations','totaldowndevices','totalpppoe','powermonsdown'));
    }
    public function getFizWhatsappReport(){
        $locations = Location::where('site_type','fiz')->with('device')->get();
        $totaldowndevices = HomeController::getDownDevicesCount();
        $totalpppoe =  HomeController::getTotalPppoe();
        $powermonsdown = HomeController::getDownPowerMons();
        return view('fiz-panel-whatsapp',compact('locations','totaldowndevices','totalpppoe','powermonsdown'));
    }

    public function warnings(){
        $warnings = \DB::table('interfaces_on_threshhold')->get();
        return view('dinterface.warnings',compact('warnings'));
    }

    public function report(){
        $warnings = \DB::table('interfaces_on_threshhold')->get();
        return view('dinterface.report',compact('warnings'));
    }



}


//
//        $totalpppoe = \DB::table('devices')->where('ping','!=','0')->sum('active_pppoe');
//
//        $pppoechart = \Lava::DataTable();
//
//        $pppoechart->addStringColumn('Type')
//            ->addNumberColumn('Value')
//            ->addRow(['Active PPPOE', $totalpppoe]);
//        Location::getStatusCheck();
//        \Lava::GaugeChart('pppoechart', $pppoechart, [
//            'width'      => 250,
//            'greenFrom'  => 2100,
//            'greenTo'    => 2350,
//            'yellowFrom' => 1300,
//            'yellowTo'   => 2099,
//            'redFrom'    => 0,
//            'redTo'      => 1299,
//            'height'     => 800,
//            'max'        => 2350,
//            'majorTicks' => [
//                'Critical',
//                'Good'
//            ]
//        ]);
//
//        $totaldowndevices = \DB::table('devices')->where('ping', '!=', 1)->count();
//        $totaldowndeviceslist = \DB::table('devices')->where('ping', '!=', 1)->get();
//        $totaldownlocationslist= Location::where('status', '<', 0)->get();
//
//        $downdeviceschart = \Lava::DataTable();
//
//        $downdeviceschart->addStringColumn('Type')
//            ->addNumberColumn('Value')
//            ->addRow(['Devices Down', $totaldowndevices]);
//
//        \Lava::GaugeChart('downdeviceschart', $downdeviceschart, [
//            'width'      => 250,
//            'greenFrom'  => 0,
//            'greenTo'    => 15,
//            'yellowFrom' => 16,
//            'yellowTo'   => 75,
//            'redFrom'    => 76,
//            'height'     => 800,
//            'redTo'      => 250,
//            'max'        => 250,
//            'majorTicks' => [
//                'Safe',
//                'Critical'
//            ]
//        ]);
//
//        $powermonsdown = \DB::table('devices')->where('ping', '!=', 1)->where('devicetype_id','=','4')->count();
//        $powertable = \Lava::DataTable();
//
//        $powertable->addStringColumn('Type')
//            ->addNumberColumn('Value')
//            ->addRow(['Downpower Sites', $powermonsdown]);
//
//        \Lava::GaugeChart('powertable', $powertable, [
//            'width'      => 250,
//            'greenFrom'  => 0,
//            'greenTo'    => 3,
//            'yellowFrom' => 3.00001,
//            'yellowTo'   => 7,
//            'redFrom'    => 7.00001,
//            'height'     => 800,
//            'redTo'      => 15,
//            'max'        => 15,
//            'majorTicks' => [
//                'Safe',
//                'Critical'
//            ]
//        ]);

//        $problemlocations = \DB::table('locations')->where('status', '!=', 0)->count();
//        $problocationtable = \Lava::DataTable();
//
//        $problocationtable->addStringColumn('Type')
//            ->addNumberColumn('Value')
//            ->addRow(['Down Locations', $problemlocations]);
//
//        \Lava::GaugeChart('problocationtable', $problocationtable, [
//            'width'      => 250,
//            'greenFrom'  => 0,
//            'greenTo'    => 3,
//            'yellowFrom' => 3.00001,
//            'yellowTo'   => 7,
//            'redFrom'    => 7.00001,
//            'height'     => 800,
//            'redTo'      => 15,
//            'max'        => 15,
//            'majorTicks' => [
//                'Safe',
//                'Critical'
//            ]
//        ]);

//    public function getDashboardOutages(){
//        $locations= Location::get();
//        $array = array();
//        $count = 0;
//        $count2 = 0;
//        foreach($locations as $location){
//            if ($location->status="0") {
//            }
//        foreach ($location->device as $device){
//            if($device->ping!="1") {
//                    $array[$location->name][]= $device->name;
//                }
//            $count2++;
//            }
//            $count++;
//        }
//
//        return json_encode($array);
//    }
