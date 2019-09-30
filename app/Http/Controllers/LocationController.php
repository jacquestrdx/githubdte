<?php

namespace App\Http\Controllers;

use App\Backhaul;
use App\Devicetype;
use App\DInterface;
use PEAR2\Exception;
use Illuminate\Http\Request;
use App\Locationaudit;
use App\Http\Requests;
use App\Highsiteform;
use App\Location;
use App\Bwstaff;
use App\Hscontact;
use App\Device;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DeviceController;
use Khill\Lavacharts\Lavacharts;


Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('location.index');
    }

    public function getDevicesIPsAJAX($id){
        $location = Location::find($id);
        $array = "";
        foreach($location->device as $device){
            if($device->devicetype_id == "1"){
                $array[] = '<a href="/device/'.$device->id.'" target="_blank">'.$device->ip.' - '.$device->name.'</a>';
            }
        }

        return $array;
    }

    public function getDevicesAJAX($id){
        $location = Location::find($id);
        foreach($location->device as $device){
            if($device->devicetype_id == "1"){
                $array[$device->id] = [$device->name];
            }
        }
        if (isset($array)){
            return $array;
        }else{
            return ["NO Mikrotiks"];
        }
    }

    public function getDevicesInterfacesAJAX($id)
    {
        $location = Location::find($id);
        foreach ($location->device as $device) {
            if ($device->devicetype_id == "1") {
                $array [] = $device->id;
            }
        }
        $string = "";
        foreach($array as $item){
            $interfaces [] = Device::getMikrotikInterfacesLive($item);
        }

        foreach ($interfaces as $interface) {
            foreach ($interface as $row){
                $string .= "<b>".$row['default_name']."</b>";
                $string .= "<ul><li>".$row['name']."</li></ul>";
                $string .= "<ul><li>".$row['comment']."</li></ul>";
            }
        }
        return $string;
    }

    public function getAllAjax(){
        $locations = Location::with('device')->get()->sortByDesc(function ($location) {
            return $location->device->sum('active_pppoe');
        });
        $array = array();
        foreach ($locations as $location){
            if( ($location->getDownCount($location) == sizeof($location->device) AND ($location->getDownCount($location) != "0"))){

            $array[] = [$location->id,"<a href='/location/$location->id'>$location->name</a>",$location->description,$location->lng,$location->lat,$location->device->sum('active_pppoe'),$location->device->sum('maxactivepppoe'),$location->site_type,$location->standbytime	,
                $location->getDownCount($location),sizeof($location->device),"<div style='color:red'>Offline</div>"];
            }else{
                $array[] = [$location->id,"<a href='/location/$location->id'>$location->name</a>",$location->description,$location->lng,$location->lat,$location->device->sum('active_pppoe'),$location->device->sum('maxactivepppoe'),$location->site_type,$location->standbytime	,
                    $location->getDownCount($location),sizeof($location->device),"<div style='color:green'>Online</div>"];
            }

        }

        return $array;

    }

    public function getBackhaulsAjax($id){
        $string ="";
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $backhauls = Backhaul::where('location_id',$id)->orWhere('to_location_id',$id)->lists('dinterface_id');
        $backhauls2 = Backhaul::where('location_id',$id)->orWhere('to_location_id',$id)->get();
        $interfaces2 = DInterface::whereIn('id',$backhauls)->get();
        $interfaces = DInterface::whereIn('id',$backhauls)->lists('name','device_id');

        foreach($backhauls2 as $backhaul2){
            $locations_involved[$backhaul2->dinterface->device_id] = [$backhaul2->dinterface->name,$backhaul2->getTo_location($backhaul2->location_id),$backhaul2->getTo_location($backhaul2->to_location_id),$backhaul2->dinterface->threshhold];
        }

//        foreach($interfaces2 as $interface2){
//            $interfaces_involved[] = $interface2->id;
//        }

        $array = array();
        foreach ($interfaces as $device_id=>$interface) {
            try{
                foreach ($db->query("select * from interfaces where iname='".$interface."' and host='".$device_id."' order by time desc limit 1") as $row) {
                    $array[] = $row;
                }
            }catch (\Exception $e){

            }
        }
        foreach ($array as $line){
            $linelocations = $locations_involved[$line->host];
            $results[$line->iname] = [$linelocations['1'],$linelocations['2'],$line->rxvalue,$line->txvalue,$linelocations['3']];
        }
        if (isset($results)){
            return view('location.backhauls',compact('results'));
        }else{
            return "No Backhauls defined for this location!";
        }
    }

    public function quickStockReport()
    {
        $locations = Location::with('device')->get()->sortByDesc(function ($location) {
            return $location->device->sum('active_pppoe');
        });

        $array = array();
        $devicetypes = Devicetype::get();
            foreach ($locations as $location){
                foreach ($devicetypes as $devicetype) {
                    $count[$devicetype->name] = 0;
                }
            foreach ($location->device as $device){
                foreach ($devicetypes as $devicetype){
                    if ($device->devicetype_id == $devicetype->id){
                        $counter = $count[$devicetype->name];
                        $counter++;
                        $array[$location->name][$devicetype->name] = $counter;
                        $count[$devicetype->name] = $counter + 1;
                    }
                }
            }
        }
        return view('location.quickreport', compact('array','devicetypes'));
    }

    public function acknowledge($id){
        $location = Location::find($id);
        return view('acknowledge.add_location', compact('location'));
    }

    public function detailedStockReport(){
        $locations = Location::with('device')->get();
        return view('location.detailedreport', compact('locations'));
    }

    public function generateExcelReport(){
        $filename = Location::generateExcelReport();
        return response()->download($filename);
    }

    public function highsitereport()
    {
//       $locations= Location::orderby()->get();
        //$locations =  Location::withCount('device')->orderBy('device_count', 'desc')->get();

        $locations = Location::with('device')->get()->sortByDesc(function ($location) {
            return $location->device->sum('active_pppoe');
        });

        //dd($locations);
        return view('location.report', compact('locations'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hscontacts = Hscontact::lists('name', 'id');
        return view('location.create', compact('hscontacts'));
    }

    public function map($id)
    {
        $location = Location::find($id);
        return view('location.map', compact('location'));
    }

    public function autoDiscoverForm($id){
        $location = Location::find($id);
        return view('location.scan',compact('location'));
    }

    public function autoDiscover($id){
        $location = Location::find($id);
        $input = Input::all();
        $devicetypes = Devicetype::lists('name', 'id');
        $raw = $location->autoDiscover($input['ip_range']);
        foreach($raw as $ip=> $vendor){
            $nr_vendor = 25;
            if(NULL!=strpos($vendor,'ASR')){
                $nr_vendor = 6;
            }
            if( (NULL==strpos($vendor,'ASR') and (NULL!=strpos($vendor,'Cisco')) )){
                $nr_vendor = 7;
            }
            if(NULL!=strpos($vendor,'RouterOS')){
                $nr_vendor = 1;
            }
            if(NULL!=strpos($vendor,'EdgeSwitch')){
                $nr_vendor = 2;
            }
            if(Device::where('ip','=',trim($ip))->exists()){
                $ips['exists'][] = 1;
                $ips['address'][] = $ip;
                $ips['vendor'][] = $nr_vendor;
            }else{
                $ips['exists'][] = 0;
                $ips['address'][] = $ip;
                $ips['vendor'][] = $nr_vendor;
            }
        }
        return view('location.scanned',compact('ips','location','devicetypes'));
    }

    public function addFromScan($id){
        $input = Input::all();
        $count = 1;
        $countnew = 0;
        foreach($input as $row){
            $count++;
            if($count % 4 == 0) {
                $countnew++;
            }
            $results[$countnew][] = $row;
        }
        unset($results[0]);
        foreach ($results as $result){
            if($result[2] == "Y"){
                try{
                    $device = new Device();
                    $device->devicetype_id = $result[1];
                    $device->name = $result[0];
                    $device->ip = trim($result[3]);
                    $device->location_id = $id;
                    $device->save();
                }catch (\Exception $e){

                }

            }
        }
        return redirect('/location/'.$id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input    = Input::all();
        $function = 'create';
//        $validate = LocationController::validateInput($input,$function);
//        if($validate['0']==0){
//            session(['status' => $validate['1'],'notification_type' => 'Error']);
//            return redirect("location/$function")->with(session('status'));
//        }
        if (array_key_exists('lng',$input)){
            $input['lng'] = preg_replace("/°/", "",$input['lng']);

        }
        if (array_key_exists('lat',$input)){
            $input['lat'] = preg_replace("/°/", "",$input['lat']);
        }
        $date = new \DateTime;
        $location = Location::create($input);
        $location->save();
        $formatted_date = $date->format('Y-m-d H:i:s');
        $user = Auth::user();
        $array = array(
            'user_id' => $user->id,
            'location_id' => $location->id,
            'action' => "Create",
            'device_ip' => \Request::ip(),
            'location_name' => $location->name
        );
        LocationAudit::createEntry($array);
//        session(['notification_type' => 'Success','status'=>'Updated sucessfully']);
        \Session::flash('status', 'Location successfully edited!');
        \Session::flash('notification_type', 'Success');
        return redirect("location/$location->id")->with(session('status'));
    }

    public static function validateInput($input,$function){
        if( ($input['lat']<1) or ($input['lat']>1)){
            return $array = [0,'Latitiude not filled in'];
        }
        if(($input['lng'])<1){
            return $array = [0,'Longitude not filled in'];
        }
        if(preg_match("/°/", $input['lat'], $output_array)){
            return $array = [0,'Latitiude not filled in CORRECTLY'];
        }
        if(preg_match("/°/", $input['lng'], $output_array)){
            return $array = [0,'Longitude not filled in CORRECTLY'];
        }
        return $array =[1,'Location Stored Successfully!!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location      = Location::find($id);
        $highsiteforms = Highsiteform::where('location_id', '=', "$id")->get();
        return view('location.show', compact('location', 'highsiteforms','results'));
    }

    public function showSectors($id)
    {
        $location      = Location::find($id);
        $highsiteforms = Highsiteform::where('location_id', '=', "$id")->get();
        return view('location.sectors', compact('location', 'highsiteforms'));
    }


    public function highsitefaultreport($id)
    {
        $location = Location::find($id);
        return view('location.summary', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function frequencyreport($id)
    {
        $location = Location::find($id);
        for ($x = 5100; $x <= 6000;) {
            foreach ($location->device as $device){
                if (($device->devicetype_id =="2") or ($device->devicetype_id =="8") or ($device->devicetype_id =="22") or ($device->devicetype_id =="17")){
                    $device->lower = $device->freq - ($device->channel/2);
                    $device->higher = $device->freq + ($device->channel/2);
                    if (($x >= $device->lower) AND ($x <= $device->higher)){
                        $frequencybands[$x][] = $device->ssid;
                    }else{
                        $frequencybands[$x][] = "";
                    }
                }
            }
            $x = $x + 5 ;
        }

        foreach ($location->device as $device){
            if (($device->devicetype_id == "2") or ($device->devicetype_id =="8") or ($device->devicetype_id =="22") or ($device->devicetype_id =="17")){
                $colors[$device->ssid]['0'] = Location::random_color();
                $colors[$device->ssid]['1'] = ($device->freq - ($device->channel/2)) .'Hz - '. ($device->freq + ($device->channel/2)).'Hz' ;
            }
        }


        return view('location.frequencies', compact('location','frequencybands','colors'));
    }


    public function edit($id)
    {
        $location      = Location::find($id);
        $hscontacts    = Hscontact::lists('name', 'id');
        if (!empty($location->hscontact)) {
            $currhscontact = $location->hscontact->id;
        } else {
            $currhscontact = null;
        }
        $mainbackhaullocations        = Location::orderBy('name','asc')->lists('name', 'id');
        $selectedmainbackhaullocation = $location->mainbackhaul;
        $backupbackhaullocations        = Location::orderBy('name','asc')->lists('name', 'id');
        $selectedbackupbackhaullocation = $location->backupbackhaul;


        return view('location.edit', compact('location', 'hscontacts', 'currhscontact','mainbackhaullocations','selectedbackupbackhaullocation','selectedmainbackhaullocation','backupbackhaullocations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $change = "";
        $input = Input::all();
        if (array_key_exists('lng',$input)){
            $input['lng'] = preg_replace("/°/", "",$input['lng']);

        }
        if (array_key_exists('lat',$input)){
            $input['lat'] = preg_replace("/°/", "",$input['lat']);
        }
        $function = "$id/edit";
//        $validate = LocationController::validateInput($input,$function);
//        if($validate['0']==0){
//            session(['status' => $validate['1'],'notification_type' => 'Error']);
//            return redirect("location/$function")->with(session('status'));
//        }
        $location = Location::find($id);
        if($location->name != $input['name']){
            $change .= " Name changed to ".$input['name']." from $location->name";
        }
        if($location->lng != $input['lng']){
            $change .= " lng changed to ".$input['lng']." from $location->lng";
        }
        if($location->lat != $input['lat']){
            $change .= " lat changed to ".$input['lat']." from $location->lat";
        }


        Location::find($id)->update($input);
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $user = Auth::user();
        $array = array(
            'user_id' => $user->id,
            'location_id' => $location->id,
            'action' => "Edit $change",
            'device_ip' => \Request::ip(),
            'location_name' => $location->name
        );
        LocationAudit::createEntry($array);
        \Session::flash('status', 'Location successfully edited!');
        \Session::flash('notification_type', 'Success');
//        session(['notification_type' => 'Success','status'=>'Updated sucessfully']);
        return redirect("location/$location->id")->with('status', "$location->name updated!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id){

        $location = Location::find($id);

        echo "<p> Deleting $location->name </p>";
        $user = Auth::user();

        if ($user->user_type=="admin"){
            echo "<p> Deleting devices</p>";

            $deleted = \DB::delete('delete from locations where id = '.'"'.$id.'"');
            $devicecontroller = new DeviceController();
            try{
                foreach ($location->device as $device){
                    echo "<p> Deleting $device->name</p>";
                    $devicecontroller->destroy($device->id);
                }
            }catch (\Exception $e){

            }
            $user = Auth::user();
            $array = array(
                'user_id' => $user->id,
                'location_id' => $location->id,
                'action' => "Delete",
                'device_ip' => \Request::ip(),
                'location_name' => $location->name
            );
            LocationAudit::createEntry($array);
            \Session::flash('status', 'Location successfully Deleted!');
            \Session::flash('notification_type', 'Success');
            return redirect("home");
        }else{
            return redirect("home");
        }

    }
}
