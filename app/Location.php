<?php

namespace App;

use App\Device;
use App\Locationstats;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = ['name', 'site_type','description', 'lng', 'lat', 'batteries', 'standbytime', 'mainbackhaul','mainbackhaultype','backupbackhaultype', 'backupbackhaul', 'powermonip', 'bwstaff_id', 'subnet', 'hscontact_id'];

    public function device()
    {
        return $this->hasMany('App\Device');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    public function backhauls()
    {
        return $this->hasMany('App\Backhaul');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

    public function highsiteforms()
    {
        return $this->hasMany('App\Highsiteform');
    }

    public function getPPPOECount($id){
        $location = Location::find($id);
        return $location->device->sum('active_pppoe');
    }

    public function getSectorCount($id){
        $location = Location::with('device')->find($id);
        $sectors = 0;
        foreach($location->device as $device){
            if ( ($device->devicetype_id == "2") or ($device->devicetype_id == "17") or ($device->devicetype_id == "15") or ($device->devicetype_id == "22") ){
                $sectors++;
            }
        }
        return $sectors;
    }

    public function getPossibleBackhauls(){
        $query = "select locations.name from backhauls inner join locations on location_id =locations.id where to_location_id ='$this->id'";
        $backhauls = \DB::SELECT($query);
        if (sizeof($backhauls) >= 1){
            return $backhauls;
        }else{
            return NULL;
        }
    }

    public function autoDiscover($range){
        $results = array();
        $command = "nmap -sn ".$range." 2>&1";
        exec($command,$results,$results2);
        foreach($results as $result){
            if (Null!=strpos($result,'report for')){
                if(Null!=strpos($result,"(")){
                    $temp = preg_split('/\(/', $result);
                    $temp = preg_replace('/\)/','',$temp[1]);
                    $finals[] = $temp;
                }else{
                    $finals[] = preg_replace('/Nmap scan report for/','',$result);
                }
            }
        }
        if(!isset($finals)){
            echo "Nothing SCANNED ";exit;
        }
        foreach($finals as $final){
            try{
                $vendor = snmp2_real_walk(trim($final),config('ubnt.ubnt_snmpcommunity'),'iso.3.6.1.2.1.1.1.0');
                $final_results[$final] =  $vendor["iso.3.6.1.2.1.1.1.0"] ;
            }catch (\Exception $e){
                $final_results[$final] = "n/a";
            }
        }
        return $final_results;
    }


    public function voipservers()
    {
        return $this->hasMany('App\VoipServer');
    }

    public function hscontact()
    {
        return $this->belongsTo('App\Hscontact');
    }

    public static function getDownCount($location)
    {
        $devices = Device::where("location_id", "=", "$location->id")->where('ping', '!=', '1')->count();

        return $devices;
    }


    public static function storeDailystats(){
        $locations = Location::with('device')->with('backhauls')->get();
        foreach($locations as $location){
            echo $location->id ."\n";
            $sumsectors = 0;
            foreach($location->backhauls as $backhaul){
                if(isset($backhaul)){
                    if ($backhaul->priority == '0'){
                        $mainbackhaul = $backhaul;
                    }
                }else{
                    $mainbackhaultx =  "0";
                    $mainbackhaulrx =  "0";
                }
            }
            if (isset($mainbackhaul)){
                $mainbackhaultx =  $mainbackhaul->dinterface->txspeed;
                $mainbackhaulrx =  $mainbackhaul->dinterface->rxspeed;
            }else{
                $mainbackhaultx =  "0";
                $mainbackhaulrx =  "0";
            }
            echo "Backhauls \n";
            foreach($location->device as $device){
                if(isset($device)) {
                    if (($device->devicetype_id == "2") or ($device->devicetype_id == "15") or ($device->devicetype_id == "17") or ($device->devicetype_id == "22")) {
                        $sumsectors++;
                    }
                }
            }
            echo "Devices \n";

            $array[$location->id] = array(
                "name" => $location->name,
                "clients" => $location->device->sum('active_pppoe'),
                "backhualtx" => $mainbackhaultx,
                "backhaulrx" => $mainbackhaulrx,
                "stations" => $location->device->sum('active_stations'),
                "sectors" => $sumsectors
            );
            echo "Stats \n";
        }

        echo "STARTING INSERTS \n";
        foreach($array as $key=>$line){
            $locationstats = new Locationstats();
            $locationstats->name = $line['name'];
            $locationstats->active_pppoes = $line['clients'];
            $locationstats->backhualtx = $line['backhualtx'];
            $locationstats->backhualrx = $line['backhaulrx'];
            $locationstats->stations = $line['stations'];
            $locationstats->sectors = $line['sectors'];
            $locationstats->location_id = $key;
            $locationstats->save();
        }

    }

    public static function getStatusCheck()
    {

        $locations = Location::with('device')->get();
        foreach ($locations as $location) {
            $temp = "0";
            foreach ($location->device as $device) {
                if (($device->ping != "1") AND ($device->devicetype_id != "4")) {
                    $temp = "1";
                }
            }

            if ($temp == "1") {
                $location->status = "1";
                if ($location->acknowledged == "1"){
                    $location->acknowledged = "0";
                    $location->save();
                    $acknowledgement = Acknowledgement::where('location_id',$location->id)->where('active',"1")->first();
                    $acknowledgement->active = "0";
                    $acknowledgement->save();
                }

            }
            if ($temp == "0") {
                $location->status = "0";
            }
            $location->save();

            if ($location->status != "0") {
                //echo $location->name."\n";
            }
        }
    }


    public function getMainBackhaulLat(){
        $location = Location::find($this->mainbackhaul);
        echo $location->lat;
    }
    public function getMainBackhaulLng(){
        $location = Location::find($this->mainbackhaul);
        echo $location->lng;
    }
    public function getAckUser()
    {
        $acknowledgement = Acknowledgement::where('location_id',$this->id)->where('active',"1")->orderby('created_at','DESC')->first();
            if (isset($acknowledgement)){
                $user_id = $acknowledgement->user_id;
            }else $user_id = "";

            $user = User::where('id', $user_id)->first();
            if (isset($user)){
                return $user->name;
            }else return "";
    }

    public function getAckID()
    {
        $acknowledgement = Acknowledgement::where('location_id',$this->id)->where('active',"1")->orderby('created_at','DESC')->first();
            if (isset($acknowledgement)){
                $id = $acknowledgement->id;
            }else $id = "";

            return $id;

    }

    public function getAckUpdateTime()
    {
        $acknowledgement = Acknowledgement::where('location_id',$this->id)->where('active',"1")->orderby('created_at','DESC')->first();
            if (isset($acknowledgement)) {
                $time = $acknowledgement->updated_at;
            } else $time = "";

            return $time;

    }
    public function getAcknowledgementNote(){
        $acknowledgement = Acknowledgement::where('location_id',$this->id)->where('active',"1")->orderby('created_at','DESC')->first();
            if (isset($acknowledgement)){
            return $acknowledgement->ack_note;
        }else return "";
    }

    public function getAverageDowntime($location){
        $downtime = 0;
        $count = 0;
        $sumseconds =0;
        foreach ($location->device as $device){
            if ($device->devicetype_id != "4"){
                $sumseconds += strtotime("now")-strtotime($device->lastseen);
                $count++;
            }
        }
        if ($count != 0){
            $downtime = $sumseconds/$count;
        }else{
            $downtime = 0;
        }

        if ($downtime == 0){
            return "Refreshing";
        }else{
            if ($downtime > 86400){
                return gmdate("d \d H:i:s", $downtime);
            }else{
                return gmdate("H:i:s", $downtime);
            }
        }
    }

    public static function generateExcelReport(){
        $locations = Location::get();

        header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-disposition: attachment; filename=spreadsheet.xls" );

        foreach ($locations as $location){
           echo "\n"."Name"."\t"."Ip"."\t"."Model"."\t"."Clients"."\n";
            foreach ($location->device as $device){
                echo $device->name."\t";
                echo $device->ip."\t";
                echo $device->model."\t";
                if ($device->devicetype_id == "1"){
                    echo $device->active_pppoe."\n";
                }elseif($device->devicetype_id == "2"){
                    echo $device->active_station."\n";
                }else{
                    echo "\n";
                }
            }
        }
        return "Hallo";
    }

    public static function  random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public static function random_color() {
        return Location::random_color_part() . Location::random_color_part() . Location::random_color_part();
    }
}


