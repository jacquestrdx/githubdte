<?php

namespace App\Http\Controllers;

use App\Antenna;
use App\Backhaul;
use App\Device;
use App\DInterface;
use App\Interfacelog;
use App\Ip;
use App\Jacques\InfluxLibrary;
use App\Jacques\MikrotikLibrary;
use App\Location;
use App\Devicetype;
use App\Notification;
use App\Jacques\RouterosAPI;
use App\Acknowledgement;
use App\Statable;
use App\StationProblem;
use App\Highsiteform;
use App\DeviceUpdateController;
use Illuminate\Support\Facades\Input;
use App\Fault;
use App\Deviceaudit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

//use Request;
use App\Jacques\SmtpLibrary;
use App\SlaReport;
use Khill\Lavacharts\Charts\LineChart;


class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices     = Device::orderby('active_pppoe', 'desc')->with('location')->with('devicetype')->get();
        $devicetypes = Devicetype::get();
        $date        = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        return view('device.index', compact('devices', 'devicetypes', 'formatted_date'));
    }

    public function testDeviceClients($id){
        $device = Device::find($id);
        $device->doSectorSpeedTest($id);
        return redirect('/test/results/'.$id);
    }

    public function storeSpeedResults($id){
        $input = Input::all();
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $device = Device::find($id);
        $device->last_download_test = $input['last_download_test'] ;
        $device->last_upload_test = $input['last_upload_test'];
        $device->last_speed_time = $formatted_date;
        $device->save();
        return redirect("device/$id");


    }

    public function inputSpeedResults($id){
        $device = Device::find($id);
        return view('device.sector.speedtestresults',compact('device'));
    }

    public function noLocations()
    {
        $date = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $devices        = Device::where('location_id', '=', '56')->get();
        $devicetypes    = Devicetype::get();
        return view('device.index', compact('devices', 'devicetypes', 'formatted_date'));
    }

    public function backupstatus()
    {
        $devices     = Device::get();
        $devicetypes = Devicetype::get();
        exec(" sed -i -e 's/=true/=yes/g' storage/mikrotikbackups/*");
        exec(" sed -i -e 's/=false/=no/g' storage/mikrotikbackups/*");
        return view('device.backups', compact('devices', 'devicetypes'));
    }

    public static function secureRouterPost(Request $ip){
        $input = Input::all();
        $themikrotiklibrary = new MikrotikLibrary();
            if ($themikrotiklibrary->fix_hacked_router($input['ip'])){
                \Session::flash('status', 'Device successfully secured!');
                \Session::flash('notification_type', 'Success');
                return view('hacked.success');
            }else{
                \Session::flash('status', 'Device failed to login!');
                \Session::flash('notification_type', 'Error');
                return view('hacked.failure');
            }

    }

    public static function secureRouterForm(){
        return view('hacked.secure');
    }



    public function showMinMaxInterfaces($id){
        $device = Device::find($id);
        $time = "now() - 15h";
        $device = Device::find($id);
        $influxresult = $device->getMinMaxInterfaces($device,$time);
        $count =0;
        foreach ($influxresult as  $key => $row){
            $date = preg_split("/\T/", $row->time);
            $time = preg_split("/\./", $date['1']);
            $time = preg_split("/\:/",$time['0']);
            $hour = ($time['0']+ 2);
            $minutes = $time['1'];
            $seconds = $time['2'];
            if ($hour < 10){
                $hour = "0".$hour;
            }
            $time = $hour.":".$minutes;
            $newtime = $date['0'] . " " . $time;
            $row->time = preg_replace("/Z/","",$newtime);
                $array[$row->iname][] = array(
                    "time" => $row->time,
                    "host" => $row->host,
                    "maxrx" => $row->maxrx,
                    "maxrxtime" => $row->maxrxtime,
                    "maxtx" => $row->maxtx,
                    "maxtxtime" => $row->maxtxtime,
                    "value" => 1
                );

        }

        return view('device.mikrotik.interfacereport',compact('array'));
        //return view('device.interfaces.all',compact('devices','instantdevice'));
    }

    public function showMinMaxInterfacesAJAX($id){
        $time = "now() - 4h";
        $device = Device::find($id);
        $array = $device->getMinMaxInterfaces($device,$time);
        echo json_encode($array);
    }


    public function backupdevice($id)
    {
        $device = Device::find($id);
        $filename = $device->backupMikrotik($device);
        $device->save();
    }

    public function downloadbackup($id)
    {
        $filenames = array();
        $device = Device::find($id);
        exec("ls ".config('mikrotik.backup_storage').$device->ip.'.*',$results);

        foreach ($results as $result){
            $filename = preg_split("/\//",$result);
            $filenames[] = $filename['7'];
        }
        return view('backups.list',compact('filenames'));
    }

    public function getDiff($file1,$file2){

        $file1 = config('mikrotik.backup_storage').$file1;
        $file2 = config('mikrotik.backup_storage').$file2;

        require_once config('filediff.diff');

        // Include two sample files for comparison
        $a = explode("\n", file_get_contents($file1));
        $b = explode("\n", file_get_contents($file2));


        // Options for generating the diff
        $options = array(
           // 'ignoreWhitespace' => true,
            'ignoreCase' => true,
        );

        // Initialize the diff class
        $diff = new \Diff($a, $b, $options);
        // Generate a side by side diff

        require_once config('filediff.sidebyside');
        $renderer = new \Diff_Renderer_Html_SideBySide;
        return view('backups.diff',compact('diff','renderer'));

}

    public function getbackup($file){
        $thefile = config('mikrotik.backup_storage').$file;
        return response()->download($thefile);
    }


    public function sortby($id)
    {
        if ($id != "1" or $id != "2" or $id != "4") {
            $devices = Device::where('devicetype_id', '=', "$id")->get();
        }
        if ($id == "1") {
            $devices = Device::where('devicetype_id', '=', "$id")->orderby('active_pppoe', 'desc')->get();
        }

        if ($id == "2") {
            $devices = Device::where('devicetype_id', '=', "$id")->orderby('active_stations', 'desc')->get();
        }

        if ($id == "4") {
            $devices = Device::where('devicetype_id', '=', "$id")->get();
        }
        $date = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $devicetypes    = Devicetype::get();
        return view('device.index', compact('devices', 'devicetypes', 'formatted_date'));
        //return redirect("device")->with('devices','devicetypes','formatted_date');
    }

    public function scheduleSoftwareUpdates()
    {
        $devices     = Device::where('devicetype_id', '=', '1')->orderby('sch_update', 'desc')->get();
        $devicetypes = Devicetype::get();
        return view('device.updater', compact('devices', 'devicetypes'));
    }


    public function getFaultyDevices()
    {
        $devices     = Device::where('fault', '=', "1")->get();
        $devicetypes = Devicetype::get();
        return view('device.index', compact('devices', 'devicetypes'));
    }


    public function down()
    {
        $devices = Device::where('ping', '!=', 1)->get();
        return view('device.down', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $devices     = Device::lists('name', 'ping');
        $devicetypes = Devicetype::lists('name', 'id');
        $locations   = Location::orderby('name')->lists('name', 'id');
        $antennas = Antenna::lists('description', 'id');

        return view('device.create', compact('device', 'devices', 'locations', 'devicetypes','antennas'));
    }

    public function createfromlocations($id)
    {
        $devices     = Device::lists('name', 'ping');
        $devicetypes = Devicetype::lists('name', 'id');
        $locations   = Location::lists('name', 'id');
        $location = Location::find($id);

        return view('device.createfromlocations', compact('device', 'devices', 'locations', 'devicetypes','location'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getAllAjax(){
        $devices = Device::with('location')->with('devicetype')->get();
        $array = array();
        foreach ($devices as $device){
            if ($device->ping == "1"){
                $ping = "<p style='color:green';>"."Online"."</p>";
            }else{
                $ping = "<p style='color:red';>"."Offline"."</p>";
            }
            try{
                $serial =  $device->serial_no;
                $array[] = [
                "<a href='/device/$device->id'>"."$device->name"."</a>",
                "<a href='/device/$device->id'>"."$device->ip"."</a>",
                "<a href='/location/$device->location_id'>".$device->location->name."</a>",
                $device->devicetype->name,
                    $device->active_pppoe,
                    $device->maxactivepppoe,
                    $device->active_hotspot,
                    $device->max_active_hotspot,
                    $device->active_stations,
                    $device->max_active_stations,
                    $serial,
                $ping,
                "<a href='/device/$device->id/edit'>"."$device->ip"."</a>",
                "<a href='/device/updatenow/$device->id'>"."Poll"."</a>",
            ];
            }catch(\Exception $e){
            }
        }

        return $array;

    }

    public function store(Request $request)
    {

        $input = Input::all();

        // Append the shorter string to the longer string
        $device = Device::create($input);

        if(array_key_exists('voltage_monitor',$input)){
            $device->voltage_monitor = (int)$input['voltage_monitor'];
            $device->save();
        }else{
            $device->voltage_monitor = "0";
            $device->save();
        }
        if(array_key_exists('voltage_threshold',$input)){
            $device->voltage_threshold = ((int)$input['voltage_threshold'] * 100);
            $device->save();
        }else{
            $device->voltage_threshold = 0;
        }
        if(array_key_exists('voltage_offset',$input)){
            $device->voltage_offset = ((int)$input['voltage_offset'] * 100);
            $device->save();
        }else{
            $device->voltage_offset = 0;
        }

        $location_id = $input["location_id"];
        $location    = Location::find($location_id);

        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $user = Auth::user();
        $array = array(
            'user_id' => $user->id,
            'device_id' => $device->id,
            'action' => "Create",
            'device_ip' => \Request::ip(),
            'device_name' => $device->name
        );
        Deviceaudit::createEntry($array);
        \Session::flash('status', 'Device successfully added!');
        \Session::flash('notification_type', 'Success');
        $highsiteforms = Highsiteform::where('location_id', '=', "$location_id")->get();
        return view('location.show', compact('location','highsiteforms'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $count =0;
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $labels = array();
        $array = array();
        $device = Device::with('location')->with('bgppeers')->with('statables')->with('pppoes')->find($id);
        if (ISSET($device->fault_description)) {
            $faultdescriptions = preg_split("/,/", $device->fault_description);
            $now               = $device->lastseen;
        } else $faultdescriptions = array();

        $rrdFile ="/var/www/html/dte/rrd/pings/".trim($device->ip).".rrd";
        $result = \rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-86400), "--end", (time()-350) ) );
        if(isset($result['data'])) {

            foreach ($result["data"] as $key => $value) {
                $labels = array();
                foreach ($value as $time => $row) {
                    $array[$key][] = $row;
                    $labels[] = $time;
                }
            }
            foreach ($labels as $value) {
                $formatted_timestamps[] = date("F-j-Y g:i a", $value);
            }

            foreach ($array as $key => $row) {
                foreach ($row as $index => $value) {
                    if (is_finite($value)) {
                        $array[$key][$index] = $value;
                    } else {
                        $array[$key][$index] = 0;
                    }
                }
            }

            $ping_chart = new \App\Charts\LineChart();
            $ping_chart->labels($formatted_timestamps);
            $ping_chart_render = true;
            foreach ($array as $key => $value) {
                $count++;
                if ($key == "packet_loss") {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[0])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                } else {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                }
            }
            $options_ping_chart = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');
            foreach ($array["avg"] as $row) {
                if (is_finite($row)) {
                    if ($row < 0) {
                        $newarray[] = 0;
                    } else {
                        $newarray[] = 100;
                    }
                } else {
                    $newarray[] = 0;
                }
            }
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');

            $availibilty_ping_chart = new \App\Charts\LineChart();
            $availibilty_ping_chart->labels($formatted_timestamps);
            $availibilty_ping_chart_render = true;
            $availibilty_ping_chart->dataset($device->ip . " (%)", "line", $newarray)
                ->color($colorarray[1])
                ->lineTension(0)
                ->options([
                    'pointRadius' => '1',
                ]);
            $availibilty_ping_chart_options = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $availibilty_ping_chart->options($availibilty_ping_chart_options);
            $availibilty_ping_chart->loaderColor('blue');
        }

        return view('device.show', compact('availibilty_ping_chart','availibilty_ping_chart_render','device', 'faultdescriptions', 'now','ping_chart','ping_chart_render', 'formatted_date','notifications','stats','dayuptime','weekuptime','monthuptime'));
    }

    public function showALLSMTP(){
        $devices = Device::where('devicetype_id',"20")->get();
        return view('device.smtp.smtpservers',compact('devices'));
    }

    public function showVoltges(){
        $devices = Device::where('devicetype_id','1')->orWhere('devicetype_id','34')->get();
        return view('device.voltages',compact('devices'));
    }

    public function showIPs(){
        return view('device.ips');
    }

    public function getIPsAJAX(){
        $ips = IP::with('device')->get();
        foreach ($ips as $ip){
            $name = $ip->device->name ?? $name = "Device has been deleted";
            $id = $ip->device->ip ?? $id = "Device has been deleted";
            $array[] = [
                $ip->id,
                "<a href='/device/".$ip->device_id."'>".$name."</a>",
                $ip->address,
                date_format($ip->updated_at,"Y/m/d H:i:s"),

            ];
        }

        return $array;
    }

    public function getNotificationsAllAjax(){
        $array = "";
        $date = new \DateTime;
        $date->modify('-1 days');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('client_id','<=',"0")->orderby('updated_at', 'desc')->get();

        foreach ($notifications as $notification){
            $array[] = [$notification->device->name,$notification->message,$notification->created_at->format('Y-m-d H:i:s')];
        }
        return $array;
    }

    public function signalForm(){
        return view('device.signalcalculate');
    }

    public function calculateSignal(){
        $input = Input::all();

        $noisefloor = $input['noisefloor'];
        $sectoroutput = $input['sectoroutput'];
        $sectorgain = $input['sectorgain'];
        $cpegain = $input['cpegain'];
        $distance = $input['distance'];
        $freq = $input['freq'];

        $gain = $sectoroutput+$sectorgain+$cpegain;
        $calcdistance = ((20)*LOG($distance,10));
        $calcfreq = (20*log($freq/1000,10));
        $gaindropoff = 6;

        $desiredsignal = round(($gain-($calcdistance+$calcfreq+92.45))-4,2);
        $maxsignal = $desiredsignal-$gaindropoff;
        $snr = $noisefloor-$desiredsignal;
        $maxsnr = $noisefloor-$maxsignal;
        $workabledistance = round(pow(10,((($sectorgain+$sectoroutput+$cpegain-92.45-4-20*LOG(($freq/1000),10)-($noisefloor+30))/20))),2);
        $maxdistance = round(pow(10,((($sectorgain+$sectoroutput+$cpegain-92.45-4-20*LOG($freq/1000,10)-($noisefloor+25))/20))),2);

        echo "<br>"."Desired Signal : $desiredsignal \n";
        echo "<br>"."Max Signal : $maxsignal \n";
        echo "<br>"."SNR : $snr \n";
        echo "<br>"."Max SNR : $maxsnr \n";
        echo "<br>"."Workable Distance : $workabledistance km \n";
        echo "<br>"."Max Distance : $maxdistance km \n";
    }

    public function calculateAllStationSignal(){
        $input = Input::all();

        $noisefloor = $input['noisefloor'];
        $sectoroutput = $input['sectoroutput'];
        $sectorgain = $input['sectorgain'];
        $cpegain = $input['cpegain'];
        $distance = $input['distance'];
        $freq = $input['freq'];

        $gain = $sectoroutput+$sectorgain+$cpegain;
        $calcdistance = ((20)*LOG($distance,10));
        $calcfreq = (20*log($freq/1000,10));
        $gaindropoff = 0;

        $desiredsignal = round(($gain-($calcdistance+$calcfreq+92.45))-4,2);
        $maxsignal = $desiredsignal+$gaindropoff;
        $snr = $noisefloor-$desiredsignal;
        $maxsnr = $noisefloor-$maxsignal;
        $workabledistance = round(pow(10,((($sectorgain+$sectoroutput+$cpegain-92.45-4-20*LOG(($freq/1000),10)-($noisefloor+30))/20))),2);
        $maxdistance = round(pow(10,((($sectorgain+$sectoroutput+$cpegain-92.45-4-20*LOG($freq/1000,10)-($noisefloor+25))/20))),2);

        echo "<br>"."Desired Signal : $desiredsignal \n";
        echo "<br>"."Max Signal : $maxsignal \n";
        echo "<br>"."SNR : $snr \n";
        echo "<br>"."Max SNR : $maxsnr \n";
        echo "<br>"."Workable Distance : $workabledistance km \n";
        echo "<br>"."Max Distance : $maxdistance km \n";
    }

    public function getNotificationsAllCSV(){
        $array = "";
        $date = new \DateTime;
        $date->modify('-2 days');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('client_id','==',"0")->orderby('updated_at', 'desc')->get();

        foreach ($notifications as $notification){
            $array[] = [$notification->device->name,$notification->message,$notification->created_at->format('Y-m-d H:i:s')];
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=galleries.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $callback = function() use ($array)
        {
            $FH = fopen('php://output', 'w');
            foreach ($array as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers); //use Illuminate\Support\Facades\Response;

    }


    public function showSMTP($id){
        $device = Device::find($id);
        if ($device->devicetype_id =="20"){
            $data = $device->getSMTPServerQueue($device->id);
            $date = new \DateTime;
            $date->modify('-600 minutes');
            $formatted_date = $date->format('Y-m-d H:i:s');
            $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('device_id',"$device->id")->orderby('updated_at', 'desc')->get();
            return view('device.smtp.queuetable', compact('device', 'faultdescriptions', 'now', 'formatted_date','notifications','stats','data'));
        }
    }

    public function getChart($id){
    }

    public function getHighestInterfacesAJAX(){
        $array = Device::getHighestMikrotikInterfaces();
        echo json_encode($array);
    }

    public function getAllinterfaces(){
        $instantdevice = Device::find("1");
        $devices = Device::getAllMikrotikInterfaces();
        return view('device.interfaces.all',compact('devices','instantdevice'));
    }

    public function getHighestLowest(){
        $instantdevice = Device::find("1");
        $devices = Device::getHighestMikrotikInterfaces();
        return view('device.interfaces.all',compact('devices','instantdevice'));
    }


    public function sort_by_txvalue ($a, $b)
    {
        return $a['txvalue'] - $b['txvalue'];
    }

    public function showDevicePings($id){
        $count =0;
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $labels = array();
        $array = array();
        $device = Device::with('location')->with('bgppeers')->with('statables')->with('pppoes')->find($id);
        if (ISSET($device->fault_description)) {
            $faultdescriptions = preg_split("/,/", $device->fault_description);
            $now               = $device->lastseen;
        } else $faultdescriptions = array();

        $rrdFile ="/var/www/html/dte/rrd/pings/".trim($device->ip).".rrd";
        $result = \rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-86400), "--end", (time()-350) ) );
        if(isset($result['data'])) {

            foreach ($result["data"] as $key => $value) {
                $labels = array();
                foreach ($value as $time => $row) {
                    $array[$key][] = $row;
                    $labels[] = $time;
                }
            }
            foreach ($labels as $value) {
                $formatted_timestamps[] = date("F-j-Y g:i a", $value);
            }

            foreach ($array as $key => $row) {
                foreach ($row as $index => $value) {
                    if (is_finite($value)) {
                        $array[$key][$index] = $value;
                    } else {
                        $array[$key][$index] = 0;
                    }
                }
            }

            $ping_chart = new \App\Charts\LineChart();
            $ping_chart->labels($formatted_timestamps);
            $ping_chart_render = true;
            foreach ($array as $key => $value) {
                $count++;
                if ($key == "packet_loss") {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[0])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                } else {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                }
            }
            $options_ping_chart = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');
            foreach ($array["avg"] as $row) {
                if (is_finite($row)) {
                    if ($row < 0) {
                        $newarray[] = 0;
                    } else {
                        $newarray[] = 100;
                    }
                } else {
                    $newarray[] = 0;
                }
            }
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');

            $availibilty_ping_chart = new \App\Charts\LineChart();
            $availibilty_ping_chart->labels($formatted_timestamps);
            $availibilty_ping_chart_render = true;
            $availibilty_ping_chart->dataset($device->ip . " (%)", "line", $newarray)
                ->color($colorarray[1])
                ->lineTension(0)
                ->options([
                    'pointRadius' => '1',
                ]);
            $availibilty_ping_chart_options = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $availibilty_ping_chart->options($availibilty_ping_chart_options);
            $availibilty_ping_chart->loaderColor('blue');
        }
        return view('device.pinggraphs',compact('device','ping_chart','ping_chart_render','availibilty_ping_chart_render','availibilty_ping_chart'));
    }

    public function showDevicePingsTime($id){
        $input = Input::all();
        dd($input);
        $count =0;
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $labels = array();
        $array = array();
        $device = Device::with('location')->with('bgppeers')->with('statables')->with('pppoes')->find($id);
        if (ISSET($device->fault_description)) {
            $faultdescriptions = preg_split("/,/", $device->fault_description);
            $now               = $device->lastseen;
        } else $faultdescriptions = array();

        $rrdFile ="/var/www/html/dte/rrd/pings/".trim($device->ip).".rrd";
        $result = \rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-86400), "--end", (time()-350) ) );
        if(isset($result['data'])) {

            foreach ($result["data"] as $key => $value) {
                $labels = array();
                foreach ($value as $time => $row) {
                    $array[$key][] = $row;
                    $labels[] = $time;
                }
            }
            foreach ($labels as $value) {
                $formatted_timestamps[] = date("F-j-Y g:i a", $value);
            }

            foreach ($array as $key => $row) {
                foreach ($row as $index => $value) {
                    if (is_finite($value)) {
                        $array[$key][$index] = $value;
                    } else {
                        $array[$key][$index] = 0;
                    }
                }
            }

            $ping_chart = new \App\Charts\LineChart();
            $ping_chart->labels($formatted_timestamps);
            $ping_chart_render = true;
            foreach ($array as $key => $value) {
                $count++;
                if ($key == "packet_loss") {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[0])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                } else {
                    $ping_chart->dataset($key . " (ms)", "line", $value)
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                }
            }
            $options_ping_chart = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');
            foreach ($array["avg"] as $row) {
                if (is_finite($row)) {
                    if ($row < 0) {
                        $newarray[] = 0;
                    } else {
                        $newarray[] = 100;
                    }
                } else {
                    $newarray[] = 0;
                }
            }
            $ping_chart->options($options_ping_chart);
            $ping_chart->loaderColor('blue');

            $availibilty_ping_chart = new \App\Charts\LineChart();
            $availibilty_ping_chart->labels($formatted_timestamps);
            $availibilty_ping_chart_render = true;
            $availibilty_ping_chart->dataset($device->ip . " (%)", "line", $newarray)
                ->color($colorarray[1])
                ->lineTension(0)
                ->options([
                    'pointRadius' => '1',
                ]);
            $availibilty_ping_chart_options = array(
                "responsive" => "true",
                "displayLegend" => "true"
            );
            $availibilty_ping_chart->options($availibilty_ping_chart_options);
            $availibilty_ping_chart->loaderColor('blue');
        }
        return view('device.pinggraphs',compact('device','ping_chart','ping_chart_render','availibilty_ping_chart_render','availibilty_ping_chart'));
    }


    function getDevicePingsAJAX($id){
        $device = Device::find($id);

        $influx = new InfluxLibrary();
        $query = "SELECT * FROM pings where host ='".$device->ip."' order by time desc limit 1000 ";
        $stats = $influx->selectFromDb($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->value
                );
            }
        }
        echo json_encode($array);
    }

    function getDeviceDayPingsAJAX($id){
        $device = Device::find($id);

        $influx = new InfluxLibrary();
        $query = "SELECT * FROM one_day.pings where host ='".$device->ip."' order by time desc limit 1000 ";
        $stats = $influx->selectFromDb($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->mean
                );
            }
        }
        echo json_encode($array);
    }

    function getDeviceMonthPingsAJAX($id){
        $device = Device::find($id);

        $influx = new InfluxLibrary();
        $query = "SELECT * FROM one_month.pings where host ='".$device->ip."' order by time desc limit 1000 ";
        $stats = $influx->selectFromDb($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->mean
                );
            }
        }
        echo json_encode($array);
    }

    function getDeviceYearPingsAJAX($id){
        $device = Device::find($id);

        $influx = new InfluxLibrary();
        $query = "SELECT * FROM one_year.pings where host ='".$device->ip."' order by time desc limit 1000 ";
        $stats = $influx->selectFromDb($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->mean
                );
            }
        }
        echo json_encode($array);
    }

    public function showNetworkMap(){
        $templocations = Location::with('device')->where('lng','>','0')->orwhere('lat','<','0')->get();
        foreach ($templocations as $templocation){
                $locations[] = $templocation;
        }
        $backhauls = Backhaul::with('backhaultype')->get();

        return view('device.networkmap',compact('locations','backhauls'));
    }

    public function showNetworkMapNodes(){
        $devices = Device::get();
        $count = 0;
        $nodes = "";
        foreach ($devices as $device){
            $count++;
            if ($count == (sizeof($devices))){
                $nodes .= "{ id: $device->id , label: $device->name }";
            }else{
                $nodes .= "{ id: $device->id , label: $device->name },";
            }
        }
    }


    public function getDeviceUptime($id,$time){
        $device = Device::find($id);
        $influx = new InfluxLibrary();
        $query = "SELECT * FROM pings where host ='".$device->ip."' and time > '".$time ."' order by time desc";
        $stats = $influx->selectFromDb($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->value
                );
            }
        }
    }

    function getDeviceStatsAJAX($id)
    {
        $device = Device::find($id);
        $array = array();
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $formatted_timestamps = array();

        if($device->devicetype_id == "1"){
            $rrdFile = "/var/www/html/dte/rrd/mikrotiks/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "2") or ($device->devicetype_id == "10") or ($device->devicetype_id == "11") or ($device->devicetype_id == "22")){
            $rrdFile = "/var/www/html/dte/rrd/ubnts/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "6") or ($device->devicetype_id == "7")){
            $rrdFile = "/var/www/html/dte/rrd/ciscos/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "8") or ($device->devicetype_id == "19")){
            $rrdFile = "/var/www/html/dte/rrd/ligowaves/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "29")){
            $rrdFile = "/var/www/html/dte/rrd/intracoms/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "5")){
            $rrdFile = "/var/www/html/dte/rrd/siaes/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "17")){
            $rrdFile = "/var/www/html/dte/rrd/cambiums/" . $device->id . ".rrd";
        }
        if( ($device->devicetype_id == "14")){
            $rrdFile = "/var/www/html/dte/rrd/airfibres/" . $device->id . ".rrd";
        }
        $options = array(
            config('rrd.ds'),
            "--resolution" ,
            config("rrd.step"),
            "--start", (time() - 86400),
            "--end", (time() - 350)
        );
        $result = rrd_fetch($rrdFile, $options);
        if ($result != false) {
            foreach ($result["data"] as $key => $value) {
                $labels = array();
                foreach($value as $time => $row){
                    $set =0;
                    if(is_finite($row)){
                        if ($key == "tx_utilization") {
                            $array[$key][] = $row / 100;
                            $set = 1;
                        }
                        if ($key == "rx_utilization") {
                            $array[$key][] = $row / 100;
                            $set = 1;
                        }
                        if ($key == "rx_max_utilization") {
                            $array[$key][] = $row / 1000000;
                            $set = 1;
                        }
                        if ($key == "tx_max_utilization") {
                            $array[$key][] = $row / 1000000;
                            $set = 1;
                        }
                        if($set ==0){
                            $array[$key][] = $row;
                            $set =1;
                        }
                    }else{
                        $array[$key][] =0;
                    }
                    $labels[] = $time;
                }
            }
            if($device->devicetype_id =="29"){
                foreach($array["rx_max_utilization"] as $key=> $max_util){
                    $array["Usage Rx %"][] = round($array["rx_utilization"][$key]/(($array["rx_max_utilization"])[$key]+1)*100,2);
                    $array["Usage Tx %"][] = round($array["tx_utilization"][$key]/(($array["tx_max_utilization"])[$key]+1)*100,2);
                }
            }

            foreach ($labels as $value){
                $formatted_timestamps[] = date("F-j-Y g:i a",$value);
            }
            $count =0;
            foreach ($array as $key => $values) {
                ${$key . "station_chart"} = new \App\Charts\LineChart();
                ${$key . "station_chart"}->labels($formatted_timestamps);
                ${$key . "station_chart"}->dataset(ucfirst($key), "line", $values)
                    ->color($colorarray[$count])
                    ->lineTension(0)
                    ->options([
                        'pointRadius' => '1',
                    ]);
                $count++;
                $options_interface_chart = array(
                    "title" => array(
                        "display" => "true",
                        "text" => "$key"
                    ),
                    "responsive" => "true",
                    "displayLegend" => "true"
                );
                ${$key . "station_chart"}->options($options_interface_chart);
                ${$key . "station_chart"}->loaderColor('blue');
                $charts['chart'][] = ${$key . "station_chart"};
                $charts['interface'][] = ucfirst($key);
            }
        }
        return view('device.graphsNew', compact('charts', 'statable','device'));

    }


//    public function getDevicePingTimes($id){
//        $device = Device::find($id);
//        $client = new \crodas\InfluxPHP\Client(
//            "localhost" /*default*/,
//            8086 /* default */,
//            "root" /* by default */,
//            "root" /* by default */
//        );
//
//        $db = $client->dte;
//
//        $stats = $db->query("SELECT * FROM pings where host ='".$device->ip."'");
//        if (isset($stats)) {
//            foreach ($stats as $stat) {
//                $date = preg_split("/\T/", $stat->time);
//                $time = preg_split("/\./", $date['1']);
//                $newtime = $date['0'] . " " . $time['0'];
//                $stat->time = $newtime;
//                $array[] = [$stat->time];
//            }
//        }
//        echo json_encode($array);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device           = Device::find($id);
        $voltage_monitor  = $device->voltage_monitor;
        $devices          = Device::lists('ip', 'id');
        $selectedparent = $device->default_gateway_id;
        $devicetypes      = Devicetype::lists('name', 'id');
        $selectedtype     = $device->devicetype->id;
        $locations        = Location::orderBy('name','asc')->lists('name', 'id');
        $selectedlocation = $device->location->id;
        $antennas        = Antenna::orderBy('description','asc')->lists('description', 'id');
        if (isset($device->antenna->id)){
            $selectedantenna = $device->antenna->id;
        }else{
            $selectedantenna = 0;
        }
        return view('device.edit', compact('voltage_monitor','device', 'devices','antennas','selectedantenna','locations', 'selectedlocation', 'devicetypes', 'selectedtype','devices','selectedparent'));
    }

    public function changePassword($id)
    {
        $device           = Device::find($id);
        return view('device.password',compact('device'));
    }

    public function updatePassword($id)
    {
        $user = Auth::user();
        $beforedevice = Device::find($id);
        $input = Input::all();
        Device::find($id)->update($input);
        $device = Device::find($id);
        \Session::flash('flash_message', 'Devices successfully updated!');
        return redirect("device/$id");
    }


        public function createConfig(){
        return view('device.createconfig');
    }

    public function downloadConfig(){
        $input = Input::all();

        $phpConfigurePPPoe = $input["ConfigurePPPoE"];

        if($phpConfigurePPPoe==1){
            $phpConfigurePPPoe = "yes";
            $phpClientPPPoEName = $input["ClientPPPoEName"];
            $phpClientPPPoEPassword = $input["ClientPPPoEPassword"];
        }else{
            $phpConfigurePPPoe = "no";
            $phpClientPPPoEName = "";
            $phpClientPPPoEPassword = "";
        }
        $phpUseWireless = $input['UseWireless'];

        if($phpUseWireless==1){
            $phpUseWireless = "yes";
            $phpSSID = $input["SSID"];
            $phpWiFiPassword = $input["WiFiPassword"];
        }else{
            $phpUseWireless = "no";
            $phpSSID = "";
            $phpWiFiPassword ="";
        }

        $phpPrivateGatewayAndNetmask = $input["PrivateGatewayAndNetmask"];

        $phpDHCPservBool = $input["DHCPservBool"];

        if($phpDHCPservBool==1){
            $phpDHCPservBool = "yes";
            $phpDHCPNetworkAndMask = $input["DHCPNetworkAndMask"];
            $phpDHCPGateway = $input["DHCPGateway"];
            $phpDHCPPoolStart = $input["DHCPPoolStart"];
            $phpDHCPPoolEnd = $input["DHCPPoolEnd"];
        }else{
            $phpDHCPservBool = "no";
            $phpDHCPNetworkAndMask = "";
            $phpDHCPNetworkAndMask = "";
            $phpDHCPGateway = "";
            $phpDHCPPoolStart = "";
            $phpDHCPPoolEnd = "";
        }

        $firstpart = "# STANDARD CONFIG OF A BRONBERGWISP Mikrotik RB ##

            ###### WAN #####
            ###### PPPoE USERNAME AND PASSWORD ##############For YES type: yes, true or 1 ######
            :global ConfigurePPPoE   ".'"'.$phpConfigurePPPoe.'"'."      
            :global ClientPPPoEName    ".'"'.$phpClientPPPoEName.'"'."
            :global ClientPPPoEPassword   ".'"'.$phpClientPPPoEPassword.'"'."
            
            ###### INTERNAL ######
            :global PrivateGatewayAndNetmask ".'"'.$phpPrivateGatewayAndNetmask.'"'."
            
            #Do you want to setup Wireless###################For YES type: yes, true or 1 ######
            :global UseWireless    ".'"'.$phpUseWireless.'"'."      
            #Wireless SSID And Password (Use if multiple words or special characters. Also note that password needs to be 8 to 64 characters)
            :global SSID      ".'"'.$phpSSID.'"'."
            :global WiFiPassword    ".'"'.$phpWiFiPassword.'"'."
            
            #Do You Want To Enable a DHCP Server?############For YES type: yes, true or 1 ######
            :global DHCPservBool    ".'"'.$phpDHCPservBool.'"'."      
            :global DHCPNetworkAndMask   ".'"'.$phpDHCPNetworkAndMask.'"'."
            :global DHCPGateway     ".'"'.$phpDHCPGateway.'"'."
            :global DHCPPoolStart    ".'"'.$phpDHCPPoolStart.'"'."
            :global DHCPPoolEnd     ".'"'.$phpDHCPPoolEnd.'"'."
            
            
            #############################################################################################################################
            ####################################### DO NOT EDIT ANYTHING BEYOND THIS POINT ##############################################
            #############################################################################################################################
            ";
        $file = '/var/www/html/dte/storage/temp.rsc';
        // Open the file to get existing content
        // Append a new person to the file
        $secondpart = '
        /delay 2
        /log warning "Allow SSH, FTP and Telnet from only BW Ranges"
        /ip service
        set telnet address=10.0.0.0/8,192.168.0.0/16,172.16.0.0/12,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22,169.159.128.0/18
        set ssh address=10.0.0.0/8,192.168.0.0/16,172.16.0.0/12,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22,169.159.128.0/18
        set ftp address=10.0.0.0/8,192.168.0.0/16,172.16.0.0/12,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22,169.159.128.0/18
        
        /log warning "Allow Radius Login"
        /radius
        add address=41.160.80.8 secret=mikrotikAAA service=login
        /user aaa
        set use-radius=yes
        
        /log warning "Admin Password changed"
        /user set admin password=laroch007
        
        /log warning "Add Bridges"
        /interface bridge
        add name=LocalLanBridge protocol-mode=none
        add name=VoiceBridge protocol-mode=none
        
        /log warning "Changing interface Names"
        /interface ethernet
        set [ find default-name=ether1 ] name=ether1-WAN_Interface_To_BW;
        
        /if condition=(($ConfigurePPPoE="yes") or ($ConfigurePPPoE="true") or ($ConfigurePPPoE="1")) do={ \
        /log warning "Adding PPPoE Client"
        /interface pppoe-client
        add add-default-route=yes disabled=no interface=ether1-WAN_Interface_To_BW \
            name=pppoe-BronbergUplink password=\
            ("$ClientPPPoEPassword") service-name=bronwisplocal use-peer-dns=yes user=\
            ("$ClientPPPoEName");
        }
        
        /log warning "Configuring Voice VLAN"
        /interface vlan
        add interface=LocalLanBridge name=vlan10-VoiceLANSide vlan-id=10
        add interface=ether1-WAN_Interface_To_BW name=vlan333-VoiceWANSide \
            vlan-id=333;
        
        /if condition=(($UseWireless="yes") or ($UseWireless="true") or ($UseWireless="1")) do={ \
        /log warning "Setting up wireless Links"
        /interface wireless security-profiles
        set [ find default=yes ] supplicant-identity=MikroTik
        add authentication-types=wpa2-psk eap-methods="" management-protection=allowed \
            mode=dynamic-keys name=Secure supplicant-identity="" wpa2-pre-shared-key=("$WiFiPassword")
        /interface wireless
        set [ find default-name=wlan1 ] band=2ghz-b/g/n channel-width=20/40mhz-Ce \
            country="south africa" disabled=no disconnect-timeout=10s frequency=auto \
            l2mtu=1600 mode=ap-bridge security-profile=Secure ssid=("$SSID");
        }
        
        /if condition=(($DHCPservBool="yes") or ($DHCPservBool="true") or ($DHCPservBool="1")) do={ \
        /log warning "Setting up DHCP server"
        /ip pool
        add name=Local_dhcp_pool ranges=("$DHCPPoolStart-$DHCPPoolEnd")
        /ip dhcp-server
        add address-pool=Local_dhcp_pool disabled=no interface=LocalLanBridge name=Internal_DHCP_Pool
        }
        
        /log warning "Adding ports to Bridges"
        /interface bridge port
        add bridge=LocalLanBridge interface=ether2
        add bridge=LocalLanBridge interface=ether3
        add bridge=LocalLanBridge interface=ether4
        add bridge=LocalLanBridge interface=ether5
        add bridge=LocalLanBridge interface=wlan1
        add bridge=VoiceBridge interface=vlan333-VoiceWANSide
        add bridge=VoiceBridge interface=vlan10-VoiceLANSide
        
        /log warning "Adding Internal IP To Range"
        /ip address
        add address=("$PrivateGatewayAndNetmask") interface=LocalLanBridge
        
        /if condition=(($DHCPservBool="yes") or ($DHCPservBool="true") or ($DHCPservBool="1")) do={ \
        /log warning "Setting up DHCP Server Network"
        /ip dhcp-server network
        add address=("$DHCPNetworkAndMask") dns-server=("$DHCPGateway") gateway=("$DHCPGateway")
        }
        
        /log warning "Setting Up DNS on Router"
        /ip dns
        set allow-remote-requests=yes servers=41.223.24.10,41.223.24.11
        
        /log warning "Configuring Firewall to drop external DNS and Masquerade PPPoE"
        /ip firewall filter
        add action=drop chain=input comment="Drop all UDP DNS" dst-port=53 \
            in-interface=all-ppp protocol=udp
        add action=drop chain=input comment="Drop All TCP DNS" dst-port=53 \
            in-interface=all-ppp protocol=tcp
        /if condition=(($ConfigurePPPoE="yes") or ($ConfigurePPPoE="true") or ($ConfigurePPPoE="1")) do={ \
        /log warning "Masquerading PPPoE"
        /ip firewall nat
        add action=masquerade chain=srcnat out-interface=pppoe-BronbergUplink
        /log warning "Set Router Identity"
        /system identity
        set name=[/interface pppoe-client get pppoe-BronbergUplink value-name=user]
        }
        /log warning "Enable UPNP"
        /ip upnp
        set enabled=yes
        /ip upnp interfaces
        add interface=LocalLanBridge type=internal
        add interface=pppoe-BronbergUplink type=external
        /log warning "Enable Romon"
        /tool romon set enabled=yes
        /log warning "UNSETTING ALL VARIABLES";
        /set ConfigurePPPoE
        /set ClientPPPoEName
        /set ClientPPPoEPassword
        /set PrivateGatewayAndNetmask
        /set SSID
        /set WiFiPassword
        /set DHCPservBool
        /set DHCPNetworkAndMask
        /set DHCPGateway
        /set DHCPPoolStart
        /set DHCPPoolEnd
        /set UseWireless
        /delay 1
        /log warning "WELL DONE THIS SCRIPT EXECUTED SUCCESSFULLY"
        /delay 1
        /log warning "REMEMBER TO UPDATE THE ROUTER"';
        $current = $firstpart . $secondpart;
        // Write the contents back to the file
        file_put_contents($file, $current);

        return response()->download("/var/www/html/dte/storage/temp.rsc");
    }

    public function import(){
        return view('device.import');
    }

    public function downloadTemplate(){
        $my_file = 'template.csv';
        unlink($my_file);
        $my_file = 'template.csv';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file
        $data = '"IP","Name","DeviceType ID","Location ID"'."\n";
        $data .= '"10.0.0.1","SomeDevice","1","5" '."\n";
        fwrite($handle, $data);
        fclose($handle);
        return response()->download($my_file);

    }



    public function processImport(Request $request)
    {

        if ($request->file('file')->isValid()) {
            $request->file('file')->move('/var/www/html/dte/public', 'myfilename.csv');
        }

        return view('device.importconfirm');

    }

    public function tobeImportedAjax(){
        $tobeimported = file('/var/www/html/dte/public/myfilename.csv');
        foreach ($tobeimported as $row){
            $string = preg_replace('/\"/', '', $row);
            $string = preg_replace('/\\r\\n/', '', $string);
            $results[] = explode(',',$string);
        }
        foreach ($results as $result) {
            $finalresults[] = ["$result[0]", "$result[1]", "$result[2]", "$result[3]"];
        }
        return $finalresults;
    }



    public function confirmImport()
    {
        $tobeimported = file('/var/www/html/dte/public/myfilename.csv');
        foreach ($tobeimported as $row) {
            $string = preg_replace('/\"/', '', $row);
            $results[] = explode(',', $string);
        }
        foreach ($results as $result) {
            try {
                if ($result['1'] != 'Name') {
                    $device = new Device();
                    $device->name = $result['1'];
                    $device->ip = $result['0'];
                    $device->devicetype_id = $result['2'];
                    $device->location_id = $result['3'];
                    $device->save();
                }
            } catch (\Exception $e) {
                echo $e;
            }
        }

        \Session::flash('status', 'Devices successfully imported!');
        \Session::flash('notification_type', 'Success');
        return redirect("/device");
    }

    public function update($id)
    {
        $user = Auth::user();
        $action ="Update ";

        $beforedevice = Device::find($id);
        $input = Input::all();
        Device::find($id)->update($input);
        $device = Device::find($id);
        if(array_key_exists('voltage_monitor',$input)){
            $device->voltage_monitor = $input['voltage_monitor'];
            $device->save();
        }else{
            $device->voltage_monitor = 0;
            $device->save();
        }
        if(array_key_exists('voltage_threshold',$input)){
            $device->voltage_threshold = ($input['voltage_threshold'] * 100);
            $device->save();
        }else{
            $device->voltage_threshold = 0;
        }
        if(array_key_exists('voltage_offset',$input)){
            $device->voltage_offset = ($input['voltage_offset'] * 100);
            $device->save();
        }else{
            $device->voltage_offset = 0;
        }

        $faultdescriptions = array();
        $device->save();
        //return Redirect::back()->with('message','Device updated.');
        \Session::flash('flash_message', 'Device successfully updated!');
        if($beforedevice->ip != $device->ip){
            $action .= " Device ip changed from $beforedevice->ip to $device->ip";
        }

        if($beforedevice->name != $device->name){
            $action .= " Device name changed from $beforedevice->name to $device->name";
        }

        if($beforedevice->location_id != $device->location_id){
            $action .= " Device location changed from $beforedevice->location_id to $device->location_id";
        }

        if($beforedevice->devicetype_id != $device->devicetype_id){
            $action .= " Device devicetype_id changed from $beforedevice->devicetype_id to $device->devicetype_id";
        }

        $array = array(
            'user_id' => $user->id,
            'device_id' => $device->id,
            'action' => $action,
            'device_ip' => \Request::ip(),
            'device_name' => $device->name
        );

        Deviceaudit::createEntry($array);
        $date = new \DateTime;
        $date->modify('-2880 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('device_id',"$device->id")->orderby('updated_at', 'desc')->get();
        \Session::flash('status', 'Device successfully edited!');
        \Session::flash('notification_type', 'Success');
        return view('device.show', compact('device', 'faultdescriptions','formatted_date','notifications'));
    }

    public function pollHighsite($id){
        $location = Location::find($id);
        foreach ($location->device as $device){
            $device->updateDevice($device->id);
        }
        \Session::flash('flash_message', 'Devices successfully updated!');

        return redirect("location/$id");
    }

    public function showWarningLatenciesAJAX(){
        $latencies = Device::findHourlyLatencySpikes();
        return $latencies;
    }

    public function showMikrotikPPPOETable(){
        return view ('device.pppoetable');
    }

    public function showMikrotikPPPOETableAJAX(){
        $devices = Device::where('devicetype_id',"1")->get();
        foreach ($devices as $device) {
            $client = new \crodas\InfluxPHP\Client(
                "localhost" /*default*/,
                8086 /* default */,
                "root" /* by default */,
                "root" /* by default */
            );
            $db = $client->dte;
            $query = "select * from mikrotiks where host="."'".$device->id."'"."order by time desc limit 10";
            $stats = $db->query($query);
            if (isset($stats)) {
                $count = 0;
                foreach ($stats as $stat) {
                    if ($count == 0){
                        $first = $stat->value;
                    }
                    $count++;

                    $date = preg_split("/\T/", $stat->time);
                    $time = preg_split("/\./", $date['1']);
                    $time = preg_split("/\:/", $time['0']);
                    $hour = ($time['0'] + 2);
                    $minutes = $time['1'];
                    $seconds = $time['2'];
                    if ($hour < 10) {
                        $hour = "0" . $hour;
                    }
                    $time = $hour . ":" . $minutes;
                    $newtime = $date['0'] . " " . $time;
                    $stat->time = $newtime;
                    $devicename  = preg_replace('/\s/','//',$device->name);

                }

            }
            $last = $stat->value;
            $difference = $first-$last;
            $array[] = [$devicename,$difference];
        }

        return $array;
    }

    public function showWarningLatencies(){
        $latencies = Device::findHourlyLatencySpikes();
        return view('device.highlatencies',compact('latencies'));
    }



    public function updateList($id)
    {
        $input = Input::all();
        Device::find($id)->update($input);
        $device = Device::find($id);
        if (isset($input['sch_update'])) {
            $device->sch_update = "1";
        } else $device->sch_update = "0";
        $device->save();
        //return Redirect::back()->with('message','Device updated.');
        $devicetypes = Devicetype::get();
        $devices     = Device::where('devicetype_id', '=', '1')->orderby('sch_update', 'desc')->get();
        \Session::flash('flash_message', 'Device successfully updated!');

        return redirect("scheduleupdatesmt")->with('device', 'devicetypes');
    }



    public function showDeviceMap()
    {
        $locations = Location::get();
        $devices = Device::where('devicetype_id','1')->where('default_gateway_id','!=','0')->get();
        return view('device.devicemap', compact('devices','locations'));
    }

    public function showHSDeviceMap()
    {
        $nodes ="[";
        $edges ="[";
        $count = 0;
        $locations = Location::where('site_type','!=','fiz')->get();
        foreach ($locations as $location){
            foreach ($location->device as $device){
                if ($device->devicetype_id =="1"){

                if ($count == sizeof($locations)-1){
                    if($device->as_number > 0){
                        $nodes .= "{id:$device->as_number,label:'$device->name'}";
                    }
                }else{
                    if($device->as_number > 0){
                        $nodes .= "{id:$device->as_number,label:'$device->name'},";
                    }
                }
            }
        }
            $count++;
        }
        $nodes .="]";
        $edges .="]";
        return view('device.highsitemap', compact('nodes','edges'));
    }

    public function acknowledge($id)
    {
        $device = Device::find($id);
        return view('acknowledge.add_device', compact('device'));
    }
    public function shownosnmp(){
        $devices     = Device::orderby('active_pppoe', 'desc')->where('pollstatus','0')->get();
        $devicetypes = Devicetype::get();
        $date        = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        return view('device.nosnmp', compact('devices', 'devicetypes', 'formatted_date'));
    }

    public function shownosnmpAJAX(){
        $devices = Device::where('pollstatus','0')->with('location')->get();
        $array = array();
        foreach ($devices as $device){
            if ($device->ping == "1"){
                $ping = "<p style='color:green';>"."Online"."</p>";
            }else{
                $ping = "<p style='color:red';>"."Offline"."</p>";
            }
            $array[] = [
                "<a href='/device/$device->id'>"."$device->name"."</a>",
                "<a href='/device/$device->id'>"."$device->ip"."</a>",
                "<a href='/location/$device->location_id'>".$device->location->name."</a>",
                $device->devicetype->name,
                $device->active_pppoe,
                $device->active_hotspot,
                $ping,
                $device->lastdown,
                $device->lastsnmpupdate,
                "<a href='/device/$device->id/edit'>"."$device->ip"."</a>",
                "<a href='/device/updatenow/$device->id'>"."Edit"."</a>",

            ];
        }

        return $array;
    }


    public function testToCore($id){
        $device = Device::find($id);
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->speedTest($device);
    }

    public function showDeviceLinkHistory($id){
        return view('interfacelogs.show',compact('id'));
    }
    public function showDeviceAJAXLinkHistory($id){
        $interfacelogs = Interfacelog::with('device')->where('device_id',$id)->get();
        foreach ($interfacelogs as $interfacelog){
            $array[] = [
                $interfacelog->id,
                $interfacelog->device->name,
                $interfacelog->status,
                $interfacelog->created_at->format('Y-m-d H:i:s')
            ];
        }
        return $array;
    }

    public function showAllLinkHistory(){
        return view('interfacelogs.index');
    }


    public function showAllLinkHistoryAJAX(){
        $date        = new \DateTime;
        $date->modify('-2 days');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $interfacelogs = Interfacelog::with('device')->where('created_at','>',$formatted_date)->where('status','NOT LIKE','%N/a%')->get();
        foreach ($interfacelogs as $interfacelog){
            if (strpos($interfacelog->status,'false to true')){
                $status = "UP";
            }elseif(strpos($interfacelog->status,'true to false')){
                $status = "DOWN";
            }else{
                if (strpos($interfacelog->status,'speed')){
                    $status = "SPEED CHANGE";
                }else{
                    $status = "SPEED CHANGE";
                }
            }

            $array[] = [
                $interfacelog->id,
                '<a href="/device/'.$interfacelog->device->id.'">'.$interfacelog->device->name.'</a>',
                $interfacelog->status,
                $status,
                $interfacelog->created_at->format('Y-m-d H:i:s')
            ];
        }
        return $array;
    }

    public function showDeviceLinkHistoryAJAX($device){
        $interfacelogs = Interfacelog::where('device_id',$device->id)->get();
        foreach ($interfacelogs as $interfacelog){
            $array[] = [$interfacelog->id,$interfacelog->device->name,$interfacelog->status,$interfacelog->created_at->format('Y-m-d H:i:s')];
        }
        return $array;
    }


    public function showBgpPeers()
    {
        $devices = Device::get();
        return view('bgppeers.bgptable', compact('devices'));
    }
    public function showEnabledBgpPeers()
    {
        $devices = Device::get();
        return view('bgppeers.enabled', compact('devices'));
    }

    public function IndexMikrotiks(){
        $devices = Device::where('devicetype_id',"1")->get();
        $devicetypes = Devicetype::get();
        $date        = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        return view('device.mikrotik.index',compact('devices','devicetypes','formatted_date'));
    }

    public function getDeviceArray()
    {
        $devices = Device::lists("name", "as_number");
        return json_encode($devices);
    }

    public function showDownBgpPeers()
    {
        $devices = Device::get();
        return view('bgppeers.bgptableoffline', compact('devices'));
    }

    public function showMikrotikInterfacesOld($id){
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $device = Device::find($id);
        $array = array();

        if($device->devicetype_id == "1"){
            $interfaces = DInterface::where('device_id',$id)
                ->Where('type','!=',"Null0")
                ->get();
        }

        if($device->devicetype_id == "7"){
            $interfaces = DInterface::where('device_id',$id)
                ->where('type','!=',"Null0")
                ->get();
        }

        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        foreach($interfaces as $interface) {
            $array =array();
            $labels = array();
            $query = "SELECT * FROM interfaces where host ='" . $interface->id ."' and time > now() - 2d order by time asc limit 30000";
            $stats = $db->query($query);
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $newtime = preg_replace('/T/', ' ', $stat->time);
                    $newtime = preg_replace('/Z/', '', $newtime);
                    $format = 'Y-m-d H:i:s';
                    $date = \DateTime::createFromFormat($format, $newtime);
                    $date->modify('+2 hours');
                    $newtime = ($date->format('Y-m-d H:i:s'));
                    $stat->time = $newtime;
                    $labels[] = $newtime;
                    $results["$interface->default_name"]['timestamp'][] = strtotime($newtime);
                    $results["$interface->default_name"]['txvalue'][] = $stat->txvalue;
                    $results["$interface->default_name"]['rxvalue'][] = $stat->rxvalue;
                }

                foreach ($results["$interface->default_name"]['rxvalue'] as $key => $result) {
                    if (array_key_exists($key + 1, $results[$interface->default_name]['rxvalue'])) {
                        $finals[$key]['rxvalue'] = (($results[$interface->default_name]['rxvalue'][$key + 1] - $result));
                    }
                }
                foreach ($results["$interface->default_name"]['txvalue'] as $key => $result) {
                    if (array_key_exists($key + 1, $results[$interface->default_name]['txvalue'])) {
                        $finals[$key]['txvalue'] = (($results[$interface->default_name]['txvalue'][$key + 1] - $result));
                    }
                }
                foreach ($results["$interface->default_name"]['timestamp'] as $key => $result) {
                    if (array_key_exists($key + 1, $results[$interface->default_name]['timestamp'])) {
                        $finals[$key]['timestamps'] = ($results[$interface->default_name]['timestamp'][$key + 1] - $result);
                    }
                }
                if(isset($finals)){
                    foreach ($finals as $index => $final) {
                        if ($final['timestamps'] > 0) {
                            $txvalue = round((($final['txvalue'] * 8) / ($final['timestamps'])) / 1024 / 1024, 2);
                            $rxvalue = round((($final['rxvalue'] * 8) / ($final['timestamps'])) / 1024 / 1024, 2);
                            if (($txvalue < 0) or ($rxvalue < 0)) {
                            } else {
                                $array[$interface->default_name]['txvalue'][] = $txvalue;
                                $array[$interface->default_name]['rxvalue'][] = $rxvalue;
                            }
                        }
                    }
                }
            }
            foreach ($array as $key => $row) {
                $count = 0;
                unset($labels[count($labels)-1]);
                ${$key."interface_chart"} = new \App\Charts\LineChart();
                ${$key."interface_chart"}->labels($labels);
                foreach ($array as $key => $result) {
                    ${$key."interface_chart"}->dataset("$interface->name Tx" . " (Mbps)", "line", $result['txvalue'])
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                    $count++;
                    ${$key."interface_chart"}->dataset("$interface->name Rx" . " (Mbps)", "line", $result['rxvalue'])
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                    $count++;
                }
                $options_interface_chart = array(
                    "title" => array(
                        "display" => "true",
                        "text" => "$interface->name - $interface->id"
                    ),
                    "responsive" => "true",
                    "displayLegend" => "true"
                );
                ${$key."interface_chart"}->options($options_interface_chart);
                ${$key."interface_chart"}->loaderColor('blue');
                $charts["chart"][$key] = ${$key."interface_chart"} ;
                $charts["interface"][$key] = "<a href='/dinterface/".$interface->id."'>$interface->name </a>" ;

            }
        }
        return view('device.mikrotik.interfaces',compact('interfaces','device','array','charts'));
    }

    public function showMikrotikInterfaces($id){
        $interfaces = DInterface::where('type','!=','pptp-in')->where('device_id',$id)->get();
        return view('device.interfaces',compact('interfaces'));
    }
    public function showMikrotikPPPOEGraphs(){
        $devices = Device::where('devicetype_id',"1")->get();
        foreach ($devices as $device) {
            $client = new \crodas\InfluxPHP\Client(
                "localhost" /*default*/,
                8086 /* default */,
                "root" /* by default */,
                "root" /* by default */
            );
            $db = $client->dte;
            $query = "select * from mikrotiks where host="."'".$device->id."'"."order by time desc limit 100";
            $stats = $db->query($query);
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $date = preg_split("/\T/", $stat->time);
                    $time = preg_split("/\./", $date['1']);
                    $time = preg_split("/\:/", $time['0']);
                    $hour = ($time['0'] + 2);
                    $minutes = $time['1'];
                    $seconds = $time['2'];
                    if ($hour < 10) {
                        $hour = "0" . $hour;
                    }
                    $time = $hour . ":" . $minutes;
                    $newtime = $date['0'] . " " . $time;
                    $stat->time = $newtime;
                        $devicename  = preg_replace('/\s/','//',$device->name);
                    $array[$devicename][] = array(
                        "time" => $stat->time,
                        "value" => $stat->value,
                    );
                }
            }
        }
        return view('device.mikrotik.pppoegraphs',compact('interfaces','device','array'));
    }

    public function graphMikrotik($id){
        $device = Device::find($id);
        $themikrotiklibrary = new MikrotikLibrary();
        $themikrotiklibrary->storeMikrotikDInterface($device);
        return redirect("device/$id");

    }

    public function showStatableGraphs($id){
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $device = Device::find($id);
        $qam = array(
            "0" => "0",
            "1" => "4.1",
            "2" => "4.2",
            "3" => "4.3",
            "4" => "16.1",
            "5" => "16.2",
            "6" => "64",
            "7" => "128",
            "8" => "256",
            "9" => "512",
            "10" => "1024.1",
            "11" => "1024.2"
        );
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $array =array();
        $finals = array();
        if($device->devicetype_id=="29"){
        foreach($device->statables as $station) {
            $rrdFile ="/var/www/html/dte/rrd/intracoms/stations/".$station->id.".rrd";
            $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time() - 3600), "--end", (time() - 350)));
            $labels = array();
            $formatted_timestamps = array();
            if($result!=false){
                foreach ($result["data"]["distance"] as $key => $value) {
                    $labels[] = $key;
                }
                foreach ($result["data"]["distance"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['distance'][] = 0;

                    }else{
                        $array[$station->id]['distance'][] = $value;
                    }
                }
                foreach ($result["data"]["rxsignal"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['rxsignal'][] = 0;

                    }else{
                        $array[$station->id]['rxsignal'][] = $value;
                    }
                }
                foreach ($result["data"]["txsignal"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['txsignal'][] = 0;

                    }else{
                        $array[$station->id]['txsignal'][] = $value;
                    }
                }
                foreach ($result["data"]["disconnects"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['disconnects'][] = 0;

                    }else{
                        $array[$station->id]['disconnects'][] = $value;
                    }
                }
                foreach ($result["data"]["rx_snr"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['rx_snr'][] = 0;

                    }else{
                        $array[$station->id]['rx_snr'][] = $value;
                    }
                }
                foreach ($result["data"]["tx_snr"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['tx_snr'][] = 0;

                    }else{
                        $array[$station->id]['tx_snr'][] = $value;
                    }
                }
                foreach ($result["data"]["rx_rate"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['rx_rate'][] = 0;

                    }else{
                        $array[$station->id]['rx_rate'][] = $value;
                    }
                }
                foreach ($result["data"]["tx_rate"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['tx_rate'][] = 0;

                    }else{
                        $array[$station->id]['tx_rate'][] = $value;
                    }
                }
                foreach ($result["data"]["tx_utilization"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['tx_utilization'][] = 0;

                    }else{
                        $array[$station->id]['tx_utilization'][] = $value;
                    }
                }
                foreach ($result["data"]["rx_utilization"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['rx_utilization'][] = 0;

                    }else{
                        $array[$station->id]['rx_utilization'][] = $value;
                    }
                }
                foreach ($result["data"]["rx_max_utilization"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['rx_max_utilization'][] = 0;

                    }else{
                        $array[$station->id]['rx_max_utilization'][] = $value;
                    }
                }
                foreach ($result["data"]["tx_max_utilization"] as $key => $value) {
                    if(is_nan($value)){
                        $array[$station->id]['tx_max_utilization'][] = 0;

                    }else{
                        $array[$station->id]['tx_max_utilization'][] = $value;
                    }
                }
                foreach ($labels as $value){
                    $array[$station->id]['timestamps'][] = date("F-j-Y g:i a",$value);
                }
            }

        }

        foreach($device->statables as $station){
            if(array_key_exists($station->id,$array)) {

                foreach ($array[$station->id]['distance'] as $key => $result) {
                    $finals[$station->id]['distance'][] = $result;
                }

                foreach ($array[$station->id]['rx_rate'] as $key => $result) {
                    $finals[$station->id]['rx_rate'][] = $result;
                }
                foreach ($array[$station->id]['tx_rate'] as $key => $result) {
                    $finals[$station->id]['tx_rate'][] = $result;
                }
                foreach ($array[$station->id]['rxsignal'] as $key => $result) {
                    $finals[$station->id]['rxsignal'][] = $result / 100;
                }
                foreach ($array[$station->id]['txsignal'] as $key => $result) {
                    $finals[$station->id]['txsignal'][] = $result / 100;
                }
                foreach ($array[$station->id]['rx_snr'] as $key => $result) {
                    $finals[$station->id]['rx_snr'][] = $result / 100;
                }
                foreach ($array[$station->id]['tx_snr'] as $key => $result) {
                    $finals[$station->id]['tx_snr'][] = $result / 100;
                }
                foreach ($array[$station->id]['tx_utilization'] as $key => $result) {
                    $finals[$station->id]['tx_utilization'][] = $result / 100;
                }
                foreach ($array[$station->id]['rx_utilization'] as $key => $result) {
                    $finals[$station->id]['rx_utilization'][] = $result / 100;
                }
                foreach ($array[$station->id]['rx_max_utilization'] as $key => $result) {
                    $finals[$station->id]['rx_max_utilization'][] = $result / 1000000;
                }
                foreach ($array[$station->id]['tx_max_utilization'] as $key => $result) {
                    $finals[$station->id]['tx_max_utilization'][] = $result / 1000000;
                }
                foreach ($array[$station->id]['tx_max_utilization'] as $key => $result) {
                    $finals[$station->id][''][] = $result / 1000000;
                }
                foreach ($array[$station->id]['timestamps'] as $key => $result) {
                    $finals[$station->id]['timestamps'][] = $result;
                }
            }else{
                \Session::flash('status', 'No RRD for '.$station->mac);
                \Session::flash('notification_type', 'Error');
            }



        }

        foreach($finals as $key => $row){
            $finalarray[$key]['distance'][] = $row['distance'];
            $finalarray[$key]['rx_rate'][] = $row['rx_rate'];
            $finalarray[$key]['tx_rate'][] = $row['tx_rate'];
            $finalarray[$key]['rxsignal'][] = $row['rxsignal'];
            $finalarray[$key]['txsignal'][] = $row['txsignal'];
            $finalarray[$key]['rx_snr'][] = $row['rx_snr'];
            $finalarray[$key]['tx_snr'][] = $row['tx_snr'];
            $finalarray[$key]['tx_utilization'][] = $row['tx_utilization'];
            $finalarray[$key]['rx_utilization'][] = $row['rx_utilization'];
            $finalarray[$key]['rx_max_utilization'][] = $row['rx_max_utilization'];
            $finalarray[$key]['tx_max_utilization'][] = $row['tx_max_utilization'];
            $finalarray[$key]['timestamps'][] = $row['timestamps'];
        }
        if(isset($finalarray)){


            foreach ($finalarray as $key => $result) {
                $count = 0;
                ${$key."station_chart"} = new \App\Charts\LineChart();
                ${$key."station_chart"}->labels($result['timestamps'][0]);

                ${$key."station_chart"}->dataset("Rx_rate", "line", $result['rx_rate'][0])
                    ->color($colorarray[$count])
                    ->lineTension(0)
                    ->options([
                        'pointRadius' => '1',
                    ]);
                $count++;
                ${$key."station_chart"}->dataset("Tx_rate", "line", $result['tx_rate'][0])
                    ->color($colorarray[$count])
                    ->lineTension(0)
                    ->options([
                        'pointRadius' => '1',
                    ]);
                $count++;
                ${$key."station_chart"}->dataset("Rx Signal (db)", "line", $result['rxsignal'][0])
                    ->color($colorarray[$count])
                    ->lineTension(0)
                    ->options([
                        'pointRadius' => '1',
                    ]);
                $count++;
                ${$key."station_chart"}->dataset("Tx Signal (db)", "line", $result['txsignal'][0])
                    ->color($colorarray[$count])
                    ->lineTension(0)
                    ->options([
                        'pointRadius' => '1',
                    ]);
                $count++;

                $options_interface_chart = array(
                    "title" => array(
                        "display" => "true",
                        "text" => "$station->mac - $station->id"
                    ),
                    "responsive" => "true",
                    "displayLegend" => "true"
                );
                ${$key."station_chart"}->options($options_interface_chart);
                ${$key."station_chart"}->loaderColor('blue');
                $charts["chart"][$key] = ${$key."station_chart"} ;
                $mac= Statable::getMac($key);
                $charts["interface"][$key] = "<a href='/statables/pergraph/".$key."'>$mac </a>" ;
            }
        }

        return view('device.intracom.statablegraphs',compact('interfaces','device','array','charts'));
        }
    }


    public function showAllVipClients(){
        $devices = Device::where('location_id',"999")->get();
        return view('device.client.vip',compact('devices'));
    }
    public function showOfflineVipClients(){
        $devices = Device::where('location_id',"999")->where('ping','0')->get();
        return view('device.client.vip',compact('devices'));
    }
    public function showOnlineVipClients(){
        $devices = Device::where('location_id',"999")->where('ping','1')->get();
        return view('device.client.vip',compact('devices'));
    }

    public function showVoltages(){
        $devices = Device::where('voltage_monitor',"1")->get();
        return view('device.allvoltages',compact('devices'));
    }

    public function ajaxVoltages(){
        $devices = Device::where('voltage_monitor',"1")->get();
        foreach ($devices as $device){
            $devicelink = "<a href='/device/$device->id'>$device->name</a>";
            $array[] =   [$devicelink,$device->volts,$device->getVoltageThreshold($device->voltage_threshold),$device->getVoltageThreshold($device->voltage_offset),$device->voltage_seen_at];
        }
        return $array;
    }

    public function tracerouteIP($id){
        $device = Device::find($id);

        // Set initial command to be run on the server
        $command = "traceroute $device->ip";

        // Send the traceroute command to the system.
        //   Normally, the shell_exec function does not report STDERR messages.  The "2>&1" option tells the system
        //   to pipe STDERR to STDOUT so if there is an error, we can see it.
        $fp = shell_exec("$command 2>&1");

        // Save the results as a variable and send to the parse_output() function
        $output = (htmlentities(trim($fp)));
        $results = explode("\n",$output);
        dd($results);
    }

    public function getInterfaceStats($id){
        $device = Device::find($id);
        $interfaces = Device::getMikrotikInterfaces($device);

        $device = Device::find($id);
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;

        $stats = $db->query("SELECT * FROM interfaces where host ='".$device->id." order by time desc limit 200'");
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $newtime = $date['0'] . " " . $time['0'];
                $stat->time = $newtime;
                $array[$stat->name][] = array(
                    "time" => $stat->time,
                    "host" => $stat->host,
                    "name" => $stat->iname,
                    "rxvalue" => $stat->rxvalue,
                    "txvalue" => $stat->txvalue

                );
            }
        }
        echo json_encode($array);
    }
    public function showAllStations(){
        return view('device.stations');
    }



    public function showAllStationsAJAX(){
        $devices = Device::where('devicetype_id','10')->get();
            $array = array();
            $statables = Device::where('devicetype_id',"10")->get();
            foreach ($devices as $device){
                $array[] = array(
                    $device->id,
                    $device->name,
                    $device->ip,
                    $device->avg_ccq,
                    $device->airmaxq,
                    $device->airmaxc,
                    $device->txsignal,
                    $device->txpower,
                    $device->ssid
                );
            }
            echo json_encode($array);
    }


    public function showMikrotikInterfacesTable($id){
        $device = Device::find($id);
        $interfaces = Device::getMikrotikInterfaces($device);
    //dd($interfaces);
            $device = Device::find($id);

            //dd($array);
            //echo json_encode($array);

        return view('device.mikrotik.interfacetable',compact('interfaces','device','stats'));
    }

    public function showSfpLog(){
        return view('interfacelogs.sfp');
    }
    public function showSfpLogAJAX(){
        $timestamp = strtotime('yesterday midnight');
        $date = date_create();
        date_timestamp_set($date, $timestamp);
        $formatted_date = $date->format('Y-m-d H:i:s');
        $interfacelogs = Interfacelog::with('device')->where('status','like','%sfp%')->orderby('created_at','DESC')->get();
        foreach ($interfacelogs as $interfacelog){
            $array[] = [
                $interfacelog->id,
                $interfacelog->device->name,
                $interfacelog->status,
                $interfacelog->created_at->format('Y-m-d H:i:s')
            ];
        }
        return $array;
    }


    public function faultreport()
    {
        $devices = Fault::get();
        return view('device.faultreport', compact('devices'));

    }

    public function showSectors()
    {
        $sectors  = \DB::table('neigbors')->where('identity', 'like', '%sec%')->lists('address', 'identity');
        $sectors1 = \DB::table('neigbors')->where('identity', 'like', '%SOUTH%')->lists('address', 'identity');
        $sectors2 = \DB::table('neigbors')->where('identity', 'like', '%NORTH%')->lists('address', 'identity');
        $sectors3 = \DB::table('neigbors')->where('identity', 'like', '%EAST%')->lists('address', 'identity');
        $sectors4 = \DB::table('neigbors')->where('identity', 'like', '%WEST%')->lists('address', 'identity');
        $sectors5 = \DB::table('neigbors')->where('identity', 'like', '%bronbergwisp%')->lists('address', 'identity');
        $sectors6 = \DB::table('neigbors')->where('identity', 'like', '%wipronet%')->lists('address', 'identity');
        $sectors7 = \DB::table('neigbors')->where('identity', 'like', '%FP%')->lists('address', 'identity');


        $devices = \DB::table('devices')->where('devicetype_id', '=', '2')->lists('ip', 'name');
        return view('device.showsectors', compact('devices', 'sectors', 'sectors1', 'sectors2', 'sectors3', 'sectors4', 'sectors5', 'sectors6', 'sectors7'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->user_type=="admin"){
            $device = Device::find($id);
            foreach ($device->interfaces as $interface){
                $deleted = \DB::delete('delete from interface_warnings where dinterface_id = '.'"'.$interface->id.'"');
            }


            $deleted = \DB::delete('delete from usernotifications where usernotifications.interfacewarning_id not in (select id from interface_warnings)');
            $deleted = \DB::delete('delete from devices where id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from statables where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from b_g_p_peers where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from ips where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from interfaces where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from notifications where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from neighbors where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from deviceaudits where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from interfacelogs where device_id = '.'"'.$id.'"');
            $deleted = \DB::delete('delete from faults where device_id = '.'"'.$id.'"');
            $array = array(
                'user_id' => $user->id,
                'device_id' => $device->id,
                'action' => "Delete",
                'device_ip' => \Request::ip(),
                'device_name' => $device->name
            );
            Deviceaudit::createEntry($array);
            return redirect("home");
        }else{
            return redirect("home");
        }


    }


    public function updateall()
    {
        Device::updateall();
    }

    public function notification_log()
    {
        $date = new \DateTime;
        $date->modify('-10 days');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('client_id','==',"0")->orderby('updated_at', 'desc')->get();
        return view('device.notificationlog', compact('notifications'));

    }

    public function pingall()
    {

    }

    public function updatedev($id){
        $device = Device::find($id);
        Device::updateDevice($id);
        Fault::checkDeviceforFaults($device);
        return redirect("device/$device->id")->with('device');
    }

    public function rebootDevice($id)
    {
        $device = Device::find($id);
//    $device->rebootDevice();

        return redirect("device/$device->id")->with('device');

    }

    public function map(){
        $devices = Device::where('devicetype_id',"1")->get();

        return view('device.themap',compact('devices'));
    }

    public function updateSoftware($id)
    {
        $device = Device::find($id);
        $device->updateSoftware();
        return redirect("device/$device->id")->with('device');
    }

    public function mapjson(){
        $data = 'nodes: [
            {
                "id": "n0",
                "label": "A node",
                "x": 0,
                "y": 0,
                "size": 3
            },
            {
                "id": "n1",
                "label": "Another node",
                "x": 3,
                "y": 1,
                "size": 2
            },
            {
                "id": "n2",
                "label": "And a last one",
                "x": 1,
                "y": 3,
                "size": 1
            }
        ],
        edges: [
            {
                "id": "e0",
                "source": "n0",
                "target": "n1"
            },
            {
                "id": "e1",
                "source": "n1\",
                "target": "n2"
            },
            {
                "id": "e2",
                "source": "n2",
                "target": "n0"
            }
        ]"';

        return $data;
    }


}
