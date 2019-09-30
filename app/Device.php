<?php

namespace App;


use App\Ip;
use App\Jacques\AviatLibrary;
use App\Jacques\CiscoLibrary;
use App\Jacques\RRDLibrary;
use App\Jacques\DeltaPowerLibrary;
use App\Jacques\InterfacesLibrary;
use App\Jacques\IntracomLibrary;
use App\Jacques\MicroInstrument;
use App\Jacques\RuckusLibrary;
use App\Jacques\InfluxLibrary;
use App\Jacques\RouterosAPI;
use App\Jacques\UbntLibrary;
use App\Jacques\SiaeLibrary;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Database\Eloquent\Model;
use App\Pppoeclient;
use Khill\Lavacharts\Lavacharts;
use App\Deviceinterface;
use App\Jacques\MikrotikLibrary;
use App\Jacques\SmtpLibrary;
use App\Jacques\LigowaveLibrary;
use App\Jacques\DeviceNameFinder;
use Pyrus\Logger;
use App\Neighbor;
use App\Jacques\CambiumLibrary;


class Device extends Model
{
//
    protected $fillable = ['md5_password','md5_username','include_interfaces','snmp_community','last_download_test','last_upload_test','last_speed_time','api_port','license_1','license_2','ftp_port','md5_username','md5_password','voltage_monitor','antenna_id','antenna_tilt','antenna_heading','voltage_threshold','voltage_offset','ack_note','client_datatill_link','default_gateway_id','ping','ping2','ping3','ping1', 'acknowledged', 'ack_user_id', 'sch_update', 'ip', 'name', 'temp', 'cpu', 'total_memory', 'free_memory', 'active_pppoe', 'active_stations', 'avg_ccq', 'volts', 'current', 'soft', 'firm', 'ping', 'location_id', 'devicetype_id', 'created_at', 'updated_at','ssh_username','ssh_password'];
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
    public function antenna(){
        return $this->belongsTo('App\Antenna');
    }
    public function getSerial(){
        return $this->serial_no;
    }
    public function getVoltageThreshold($value)
    {
        return ($value/100);
    }
    public function setVoltageThreshold($value)
    {
        return ($value*100);
    }
    public function getVoltageOffset($value)
    {
        return ($value/100);
    }
    public function setVoltageOffset($value)
    {
        return ($value*100);
    }
    public function neighbors(){
        return $this->hasMany('App\Neighbor');
    }
    public function ips()
    {
        return $this->hasMany('App\IP');
    }
    public function customoid()
    {
        return $this->hasMany('App\Customsnmpoid');
    }
    public function pppoes()
    {
        return $this->hasMany('App\Pppoeclient');
    }
    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }
    public function acknowledgements()
    {
        return $this->hasMany('App\Acknowledgement');
    }
    public function bgppeers()
    {
        return $this->hasMany('App\BGPPeer');
    }
    public function interfaces()
    {
        return $this->hasMany('App\DInterface');
    }
    public function devicetype()
    {
        return $this->belongsTo('App\Devicetype');
    }
    public function statables()
    {
        return $this->hasMany('App\Statable');
    }
    public function dinterfaces()
    {
        return $this->hasMany('App\DInterface');
    }
    public function faults(){
        return $this->hasMany('App\Fault');
    }
    public function getStationAvg(){
        $signal = array();
        foreach($this->statables as $statable){
                $signal[] = (-1) * $statable->signal;
        }
        return round((-1) * (array_sum($signal)/sizeof($signal)),2);
    }
    public function getAckUser()
    {
        $acknowledgement = Acknowledgement::where('device_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            $user_id = $acknowledgement->user_id;
        }else $user_id = "";

        $user = User::where('id', $user_id)->first();
        if (count($user)){
            return $user->name;
        }else return "";
    }
    public function getAckID()
    {
        $acknowledgement = Acknowledgement::where('device_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            $id = $acknowledgement->id;
        }else $id = "";

        return $id;
    }
    public function getUptime(){
        $exception = false;
        $description = "No SNMP Response";
        try{
            $uptime       = snmprealwalk($this->ip, $this->snmp_community, "iso.3.6.1.2.1.1.3");
            $uptime       = preg_split("/Timeticks: /", $uptime['.1.3.6.1.2.1.1.3.0']);
            $uptime       = preg_split("/\)/", $uptime['1']);
            $uptime = preg_replace('/\(/','',$uptime['0']) ?? $this->uptime = "N/A";
            $uptime = round($uptime/100,0);
        }catch (\Exception $e){
            $exception = true;
            echo "SNMP NO RESPONSE \n";
            $fault = Fault::where('description',$description)->where('device_id',$this->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault->status)) {
                if($fault->status == 0){
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $this->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }else{
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $this->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
            exit;
        }
        if($exception == false) {
            $fault = Fault::where('description', $description)->where('device_id', $this->id)->orderBy('updated_at', 'DESC')->first();
            if (isset($fault)) {
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
        }
        $this->uptime = $uptime ?? $this->uptime = 0;
        $this->save();
    }

    public function getAcknowledgementNote(){
        $acknowledgement = Acknowledgement::where('device_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            return $acknowledgement->ack_note;
        }else return "";
    }
    public function getASNDeviceName($AS)
    {
        $device = Device::where('as_number', '=', $AS)->first();
        if (isset($device->name)) {
            return $device->name;
        } else return "Unknown";
    }
    public function getASNDeviceID($AS)
    {
        $device = Device::where('as_number', '=', $AS)->first();
        if (isset($device->name)) {
            return $device->id;
        } else return "Unknown";
    }
    public function getIdFromIp($ip){
        $ip = trim($ip);
        $ip = Ip::where('address',$ip)->first();
        if (isset($ip->id)){
            return $ip->device_id;
        }else return 0;
    }
    public function getLocationName(){
        return $this->location->name;
    }
    public function getDeviceFromID(){

    }
    public function getDeviceIDFromName($name){
        if (isset($name)){
            $devicenamefinder = new DeviceNameFinder();
            return  $devicenamefinder->getDeviceIDFromName($name);
        }else{
            return "No Name";
        }

    }
    public function getDownTimeToday(){
        return Notification::calculateDailyDowntime($this);
    }
    public function getDownTimeThisWeek(){
        return Notification::calculateWeeklyDowntime($this);
    }
    public function getDownTimeThisMonth(){
        return Notification::calculateMonthlyDowntime($this);
    }
    public static function updatedeviceLocationsfromIP(){
        $devices = Device::with('location')->get();
        $names = array();
        foreach ($devices as $device) {
            echo $device->name. "\t-\t".$device->location->name."\n";

        }

    }
    public static function importDevices($file){
        $file = file($file);
        foreach($file as $row){
            $array[] = explode(';',$row);
        }

        foreach ($array as $value){
            $device = New Device();
            $device->ip = $value['2'];
            $device->id = $value['0'];
            $device->name = $value['1'];
            $device->location_id = $value['4'];
            $device->devicetype_id = $value['5'];
            $device->save();
        }
    }
    public function getDeviceNameFromID($id){
        $devicenamefinder = new DeviceNameFinder();
        return $devicenamefinder->getDeviceNameFromID($id);
    }
    public static function getNameFromID($id){
        $devicenamefinder = new DeviceNameFinder();
        return $devicenamefinder->getDeviceNameFromID($id);
    }
    public function getDeviceHighsiteFromHighsiteID($id){
        $device = Device::find($id);
        return $device->location->name;
    }
    public function getDinterfaceID($name,$id){
        $dinterface = DInterface::where('name',$name)->where('device_id',$id)->first();
        if (isset($dinterface->id)){
            return $dinterface->id;
        }else{
            return "";
        }

    }
    public static function update_all_snmp()
    {
        $date_now = new \DateTime;
        $formatted_date_now = $date_now->format('Y-m-d H:i:s');
        $take =  Device::count()/4;
        $rrdFile = "/var/www/html/dte/rrd/system/load.rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:5min:GAUGE:900:U:U",
                "DS:10min:GAUGE:900:U:U",
                "DS:15min:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $sysload = \sys_getloadavg();
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "5min" => $sysload[0],
                "10min" => $sysload[1],
                "15min" => $sysload[2],
            ), $time);
        }
        //\Log::info("Polling Device::count() devices");
        $devices = Device::orderBy('update_started','ASC')->take($take)->get();
        foreach ($devices as $device) {
            $device->update_started = $formatted_date_now;
            $device->save();
            echo ($device->id."--".$device->name."\n");
            $command = "/usr/bin/php /var/www/html/dte/artisan PollSpesificDeviceByID $device->id  > /dev/null &";
            exec($command."\n",$out);
        }
    }
    public static function updateDevice($id)
    {
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $device = Device::find($id);
        try {
            if($device->ping =="1"){
                $device->getUptime();
                if ($device->devicetype_id == "1") {
                    $device->getGenericMikrotik();
                }
                if ($device->devicetype_id == "2") {
                    $device->getUbntSector();
                }
                if ($device->devicetype_id == "3") {
                    $device->getUbntToughswitch();
                }
                if ($device->devicetype_id == "4") {
                }
                if ($device->devicetype_id == "5") {
                    $device->getSiaeRadio();
                }
                if ($device->devicetype_id == "6") {
                    $device->updateCiscoSwitch();
                }
                if ($device->devicetype_id == "7") {
                    $device->updateCiscoSwitch();
                }
                if ($device->devicetype_id == "8") {
                    $device->getLigowave();
                }
                if ($device->devicetype_id == "9") {
                    $device->getMimosa();
                }
                if ($device->devicetype_id == "10") {
                    $device->getUbntStation();
                }
                if ($device->devicetype_id == "11") {
                    $device->getUbntAP();
                }
                if ($device->devicetype_id == "12") {
                    $device->getRadwin();
                }
                if ($device->devicetype_id == "13") {
                    $device->getWavion();
                }
                if ($device->devicetype_id == "14") {
                    $device->updateAirfibre();
                }
                if ($device->devicetype_id == "15") {
                    $device->getMikrotikWireless($device);
                }
                if ($device->devicetype_id == "16") {

                }
                if ($device->devicetype_id == "17") {
                    $device->updateCambium();
                }
                if ($device->devicetype_id == "18") {
                    $device->getGenericMikrotik();
                }
                if ($device->devicetype_id == "19") {
                    $device->getLigowaveRapidFire();
                }
                if ($device->devicetype_id == "20") {
                    $device->getSmtpServer();
                }
                if ($device->devicetype_id == "21") {

                }
                if ($device->devicetype_id == "22") {
                    $device->getUbntACPrismSector();
                }
                if ($device->devicetype_id == "23") {

                }
                if ($device->devicetype_id == "24") {

                }
                if ($device->devicetype_id == "25") {

                }
                if ($device->devicetype_id == "26") {
                    $device->getGenericMikrotik();
                }
                if ($device->devicetype_id == "27") {

                }
                if ($device->devicetype_id == "28") {
                    $device->getAviat();
                }
                if ($device->devicetype_id == "29") {
                    $device->getIntracomWirelessBase();
                }
                if ($device->devicetype_id == "30") {
                    $device->getIntracomWirelessStation();
                }
                if ($device->devicetype_id == "31") {
                    $device->getDeltaPower();
                }
                if ($device->devicetype_id == "32") {
                    $device->getIntracomWirelessPtP();
                }
                if ($device->devicetype_id == "33") {
                    $device->getIntracomWirelessPtP();
                }
                if ($device->devicetype_id == "34") {
                    $device->updateMicroInstrument();
                }
            }

        }catch(\Exception $e){
            echo $e;
        }

    }
    public function getGenericMikrotik()
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->updateMikrotik($this);
        $mikrotiklibrary->getIPs($this);
        $this->getNeighbors($this);
        $interfaceslibrary = new InterfacesLibrary();
        $interfaceslibrary->doInterfaces($this);
    }
    public function getUbntSector()
    {
        if ($this->ping = "1") {
            $this->active_stations = "0";
            try{
                $this->getConnections();
            }catch (\Exception $e){
            }

            if ($this->active_stations != "0") {
                $this->lastsnmpupdate = new \DateTime();
            }
            $this->signal = $this->getStationAvg() ??  $this->signal = 0;
            try{
                $this->getUbntInfo();
            }catch (\Exception $e){

            }
            $this->save();

        }else {
            //\Log::info("$this->id $this->name  Failed");
            $this->save();
            //\Log::info("$this->id $this->name  Failed");
        }

    }
    public function getSiaeRadio(){
        $interfacelibrary = new InterfacesLibrary();
        $interfacelibrary->doInterfaces($this);
        $theSIAElibrary = new SiaeLibrary();
        $theSIAElibrary->getWirelessInfo($this);
    }
    public function getUbntACPrismSector()
    {
        $ubntlibrary = new UbntLibrary();
        $this->getUbntInfo();
        $ubntlibrary->getUbntACPrismSector($this);
        $this->signal = $this->getStationAvg() ?? $this->signal = 0;
        $data = array(
            "host" => $this->id,
            "freq" => $this->freq,
            "txpower" => $this->txpower,
            "width" => $this->channel,
            "signal" => $this->signal,
            "noise_floor" => $this->noise_floor,
            "stations" => $this->active_stations
        );
        $rrdFile = "/var/www/html/dte/rrd/ubnts/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:freq:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
                "DS:stations:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "freq" => $data["freq"],
                "txpower" => $data["txpower"],
                "width" => $data["width"],
                "signal" => $data["signal"],
                "stations" => $data["stations"],
                "noise_floor" => $data["noise_floor"],
            ), $time);
        }
    }
    public function getLigowave(){
        $this->ip = trim($this->ip);

        try {
            $interfacelibrary = new InterfacesLibrary();
            $interfacelibrary->getInterfaces($this);
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $ligowavelibrary = new LigowaveLibrary();
            $this->channel = $ligowavelibrary->getGenericChannel($this);
            $this->freq = $ligowavelibrary->getGenericFreq($this);
            $this->rxsignal = $ligowavelibrary->getGenericRxSignal($this);
            $this->ssid = $ligowavelibrary->getGenericSsid($this);
            $this->txpower = $ligowavelibrary->getGenericTXPower($this);
            $this->rxrate = $ligowavelibrary->getGenericTxRate($this);
            $this->txsignal = $ligowavelibrary->getGenericTxSignal($this);
            $this->lastsnmpupdate = new \DateTime();
            $this->save();
        } catch (\Exception $e) {
            echo "Ligowave try catch failed with " . $e;
        }
        $this->save();
        $data = array(
            "host" => $this->id,
            "freq" => $this->freq,
            "width" => $this->channel,
            "txsignal" => $this->txsignal,
            "txpower" => $this->txpower,
            "rxsignal" => $this->rxsignal,
            "noise_floor" => $this->noise_floor
        );
        $rrdFile = "/var/www/html/dte/rrd/ligowaves/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:freq:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:txsignal:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:rxsignal:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "freq" => $data["freq"],
                "width" => $data["width"],
                "txsignal" => $data["txsignal"],
                "rxsignal" => $data["rxsignal"],
                "noise_floor" => $data["noise_floor"],
                "txpower" => $data["txpower"],
            ), $time);
        }
    }
    public function getUbntStation()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntStation($this);
        $this->getUbntInfo();
    }
    public function getUbntAP()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntAP($this);
        $this->getUbntInfo();
    }
    public function getLigowaveRapidFire(){
        $this->ip = trim($this->ip);
        try {
            $interfacelibrary = new InterfacesLibrary();
            $interfacelibrary->getInterfaces($this);

            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $ligowavelibrary = new LigowaveLibrary();

            $this->channel = $ligowavelibrary->getRapidFireChannel($this);
            $this->freq = $ligowavelibrary->getRapidFireFreq($this);
            $this->rxsignal = $ligowavelibrary->getRapidFireRxSignal($this);
            $this->ssid = $ligowavelibrary->getRapidFireSsid($this);
            $this->txpower = $ligowavelibrary->getRapidFireTXPower($this);
            $this->rxrate = $ligowavelibrary->getRapidFireTxRate($this);
            $this->txsignal = $ligowavelibrary->getRapidFireTxSignal($this);
            $this->lastsnmpupdate = new \DateTime();
//            $this->pollstatus = 1;
            $this->save();
        } catch (\Exception $e) {
//            $this->pollstatus = 0;
        }
        $data = array(
            "host" => $this->id,
            "freq" => $this->freq,
            "width" => $this->channel,
            "txpower" => $this->txpower,
            "txsignal" => $this->txsignal,
            "rxsignal" => $this->rxsignal,
            "noise_floor" => $this->noise_floor
        );
        $value = 1;
        $rrdFile = "/var/www/html/dte/rrd/ligowaves/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:freq:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:txsignal:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:rxsignal:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "freq" => $data["freq"],
                "width" => $data["width"],
                "txsignal" => $data["txsignal"],
                "rxsignal" => $data["rxsignal"],
                "noise_floor" => $data["noise_floor"],
                "txpower" => $data["txpower"],
            ), $time);
        }
        $this->save();
    }
    public function getMikrotikWireless($device){
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->updateMikrotik($device);
        $mikrotiklibrary->getTheMikrotikWireless($device);
        $data = array(
            "host" => $device->id,
            "freq" => $device->freq,
            "width" => $device->channel,
            "txpower" => $device->txpower,
            "signal" => $device->signal,
            "noise_floor" => $device->noise_floor,
            "stations" =>$device->active_stations
        );
        $rrdFile = "/var/www/html/dte/rrd/mikrotikw/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:freq:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
                "DS:stations:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "freq" => $data["freq"],
                "txpower" => $data["txpower"],
                "width" => $data["width"],
                "signal" => $data["signal"],
                "stations" => $data["stations"],
                "noise_floor" => $data["noise_floor"],
            ), $time);
        }
    }
    public function updateCambium(){
        $cambiumlibrary = new CambiumLibrary();
        $cambiumlibrary->pollviasnmp($this);
        $interfaceslibrary = new InterfacesLibrary();
        $interfaceslibrary->doInterfaces($this);
    }
    public function updateAirfibre(){
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getAirfibre($this);
        $data = array(
            "host" => $this->id,
            "txfreq" => $this->txfreq,
            "rxfreq" => $this->rxfreq,
            "txpower" => $this->txpower,
            "width" => $this->channel,
            "signal1" => $this->signal1,
            "signal2" => $this->signal2,
            "noise_floor" => $this->noise_floor
        );
        $rrdFile = "/var/www/html/dte/rrd/airfibres/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:txfreq:GAUGE:900:U:U",
                "DS:rxfreq:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:signal1:GAUGE:900:U:U",
                "DS:signal2:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "txfreq" => $data["txfreq"],
                "rxfreq" => $data["rxfreq"],
                "txpower" => $data["txpower"],
                "width" => $data["width"],
                "signal1" => $data["signal1"],
                "signal2" => $data["signal2"],
                "noise_floor" => $data["noise_floor"],
            ), $time);
        }
    }
    public function updateMicroInstrument($device){
        $microlibrary = new MicroInstrument();
        $microlibrary->PollviaSNMP($device);
    }
    public function updateCiscoSwitch(){
        $ciscolibrary = new CiscoLibrary();
        $ciscolibrary->PollviaSNMP($this);
        $interfacelibrary = new InterfacesLibrary();
        $interfacelibrary->doInterfaces($this);
    }
    public function getIntracomWirelessBase(){
        $intracomlibrary = new IntracomLibrary();
        $intracomlibrary->getWirelessInfo($this);
//        $interfacelibrary = new InterfacesLibrary();
//        $interfacelibrary->doInterfaces($this);
    }
    public function getIntracomWirelessStation(){
        $intracomlibrary = new IntracomLibrary();
        $intracomlibrary->getWirelessInfo($this);
    }
    public function getDeltaPower(){
        $deltalibrary = new DeltaPowerLibrary();
        $deltalibrary->PollviaSNMP($this);
    }
    public function getSmtpServer(){
        $smtp = new SmtpLibrary();
        $this->queue_count = $smtp->getQueueCount($this->ip);
        $this->cpu = $smtp->getCPU($this->ip);
        $this->free_memory = $smtp->getFreeMemory($this->ip);
        $this->save();

        if ($this->queue_count >= 350){
            $to      = 'jacques@bronbergwisp.co.za';
            $subject = "Mail server is running slow";
            $message = 'There are '.$this->queue_count.' messages in the queue';
            $headers = 'From: jacques@bronbergwisp.co.za' . "\r\n" .
                'Reply-To: jacques@bronbergwisp.co.za' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
        }
    }
    public function getUbntToughswitch()
    {
        $interfacelibrary = new InterfacesLibrary();
        $interfacelibrary->doInterfaces($this);
    }
    public function getMimosa(){}
    public function getRadwin(){}
    public function getAviat(){
        $interfacelibrary = new InterfacesLibrary();
        $interfacelibrary->doInterfaces($this);
        $aviatlibrary = new AviatLibrary();
        $aviatlibrary->getWirelessInfo($this);
    }
    public function getIntracomWirelessPtP(){}

    public static function resetPPPOECountMonthly(){
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $device->maxactivepppoe	= $device->active_pppoe;
            $device->save();
        }
    }
    public static function  doSectorSpeedTest($id){
        $device = Device::find($id);
        foreach($device->statables as $statable){
            //Pppoeclient::doClientSpeedTest($statable->id);
            $command = '/usr/bin/php /var/www/html/dte/artisan doClientSpeedTest '.$statable->id. '  > /dev/null &';
           \Log::info("Doing ".$command);
            exec($command);

        }

    }
    public static function checkAllPolling(){
        $devices = Device::get();

        foreach ($devices as $device){
            echo $device->id." - ".$device->name."\n";
            if (
                ($device->devicetype_id=="3") or
                ($device->devicetype_id=="4") or
                ($device->devicetype_id=="5") or
                ($device->devicetype_id=="6") or
                ($device->devicetype_id=="7") or
                ($device->devicetype_id=="12") or
                ($device->devicetype_id=="13") or
                ($device->devicetype_id=="14") or
                ($device->devicetype_id=="16") or
                ($device->devicetype_id=="20") or
                ($device->devicetype_id=="21") or
                ($device->devicetype_id=="23") or
                ($device->devicetype_id=="24") or
                ($device->devicetype_id=="25")
            ){
                $device->pollstatus="1";
                $device->lastsnmpupdate = new \DateTime();
                $device->save();
            }else{
                try {
                    $device->checkPolling($device);
                    $device->lastsnmpupdate = new \DateTime();
                    $device->save();
                }catch(\Exception $e){

                }
            }
        }
    }
    public function checkPolling($device){
        if ($device->devicetype_id=="1"){
            $device->testMikrotikPolling($device);
            $device->save();
        }else{
            $device->testSnmpPolling($device);
            $device->save();
        }
    }
    public function testMikrotikPolling($device){
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->testLogin($device);
    }
    public function testSnmpPolling($device){
        $sessionA = new \SNMP(\SNMP::VERSION_1, $device->ip, "public");
        if (isset($sessionA)){
            $this->pollstatus = 1;
            $this->save();
        }else{
            $this->pollstatus = 0;
            $this->save();
        }
    }
    public function getChildren(){
        $children = Device::where('default_gateway_id',$this->id)->get();
        return $children;
    }
    public static function SpeedtestAll(){
        $devices = Device::get();
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $themikrotiklibrary->speedTest($device);
        }

    }
    public static function findHourlyLatencySpikes(){
        $devices = Device::get();

        foreach($devices as $device){
            if($device->ping =="1"){
                $rrdfile = "/var/www/html/dte/rrd/pings/$device->ip".".rrd";
                $rrdFile ="/var/www/html/dte/rrd/pings/".trim($device->ip).".rrd";
                $result = \rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-86400), "--end", (time()-350) ) );
                $stats = array();
                foreach($result['data']['avg'] as $key=> $datum){
                    $values = array(
                        "time" => $key,
                        "value" => $datum
                    );
                    $stats[] = $values;
                }
                if (isset($stats)) {
                    foreach ($stats as $stat) {

                        if($stat['value'] > 100){
                            $array[$device->location->name][$device->name][] = array(
                                "url" => "<a href='/device/".$device->id."'>OPEN DEVICE</a>",
                                "type" => "High Latency",
                                "year" => date("Y-m-d H:i:s", $stat['time']),
                                "ip" => $device->ip,
                                "value" => $stat['value']
                            );
                        }
                    }
                }
                $stats = array();
                foreach($result['data']['packet_loss'] as $key=> $datum){
                    $values = array(
                        "time" => $key,
                        "value" => $datum
                    );
                    $stats[] = $values;
                }
                if (isset($stats)) {
                    foreach ($stats as $stat) {

                        if($stat['value'] == "100"){
                            $array[$device->location->name][$device->name][] = array(
                                "url" => "<a href='/device/".$device->id."'>OPEN DEVICE</a>",
                                "type" => "High packet loss",
                                "year" => date("Y-m-d H:i:s", $stat['time']),
                                "ip" => $device->ip,
                                "value" => $stat['value']
                            );
                        }
                    }
                }
                foreach($result['data']['jitter'] as $key=> $datum){
                    $values = array(
                        "time" => $key,
                        "value" => $datum
                    );
                    $stats[] = $values;
                }
                if (isset($stats)) {
                    foreach ($stats as $stat) {

                        if($stat['value'] > "100"){
                            $array[$device->location->name][$device->name][] = array(
                                "url" => "<a href='/device/".$device->id."'>OPEN DEVICE</a>",
                                "type" => "High jitter",
                                "year" => date("Y-m-d H:i:s", $stat['time']),
                                "ip" => $device->ip,
                                "value" => $stat['value']
                            );
                        }
                    }
                }
            }
        }

        return $array;
    }
    public static function StoreAllDInterfaces($job){
        $devices = Device::where('devicetype_id','1')->get();
        $count = ($devices->count()/5);
        $chunks = $devices->chunk($count);
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($chunks[$job] as $device){
            $themikrotiklibrary->storeMikrotikDInterface($device);
        }
    }
    public static function StoreOneDInterfaces($id){
        $device = Device::find($id);
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->storeMikrotikDInterface($device);
    }
    public static function updateDevicesGateways(){
        $devices = Device::with('location')->get();
        foreach ($devices as $device){
            if ($device->location->site_type=="fiz"){
                $device->default_gateway = $device->getDeviceHighsiteFromHighsiteID($device->default_gateway_id);
                $device->save();
            }
        }
    }
    public static function ResetDownsToday()
    {
        $devices = Device::get();

        foreach ($devices as $device) {
            $device->downs_today = 0;
            $device->save();
        }
    }
    public static function FixHackedMT($job){
        $devices = Device::where('devicetype_id',1)->get();
        $count = ($devices->count()/20);
        $chunks = $devices->chunk($count);
        foreach ($chunks[$job] as $device){
            $device->fixthis();
        }
    }
    public function fixthis(){
        $mikrotiklibrary =new MikrotikLibrary();
        try{
            $mikrotiklibrary->fix_hacked_router($this);
        }catch(\Exception $e){

        }
    }
    public static function cleanInflux(){
        $influxdb =  new InfluxLibrary();
        $timestamp = strtotime('-14 days');
        $query = "delete from pings where time < $timestamp";
        $query = "delete from interfaces where time < $timestamp";
        $influxdb->selectFromDb($query);
    }
    function getDevicePings($device,$time){
        $historicalpingworker = new HistoricalPingWorker();
        return $historicalpingworker->getDevicePings($device,$time);
    }
    function getDeviceUptime($device,$time){
        $count = 0;
        $pings = $device->getDevicePings($device, $time);
        if ($pings == "NoPingsFound"){
            echo "No pings found for $device->name \n";
        }else{
            foreach ($pings as $ping) {
                $previousping = $ping['value'] ?? $previousping = 0;
                if ($ping['value'] == "-1") {
                    $count++;
                }
            }
            $uptime = round(((sizeof($pings)) - $count) / sizeof($pings) * 100, 2);
            $results = [
                "name" => $device->name,
                "total-downtime" => $count,
                "uptime" => $uptime
            ];
            $finalresults[$device->location->name][] = $results;
        }
        if (isset($results)){
            return $results;
        }else{
            $results = [
                "name" => "",
                "total-downtime" => "",
                "uptime" => ""
            ];
            return $results;
        }
    }
    public function noDefaultGateway(){
        $array = array();
        $mikrotiks = Device::get();
        foreach ($mikrotiks as $device){
            if ($device->default_gateway == ""){
                $array[$device->id][$device->name] = $device->ip;
            }
        }
        return $array;
    }
    public static function getMikrotikInterfaces($device){
        $mikrotiklibrary = new MikrotikLibrary();
        $interfaces = $mikrotiklibrary->getMikrotikInterfaces($device);
        return $interfaces;
    }
    public static function syncInterfaces(){
        $devices = Device::get();
        $interfacelibrary = new InterfacesLibrary();
        foreach($devices as $device){
            $interfacelibrary->syncInterfaces($device);
        }
    }

    public static function checkInterfaceThreshholds(){
        //\DB::table('interfaces_on_threshhold')->where('id', '>', 0)->delete();
        $mikrotiklibrary = new MikrotikLibrary();
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $mikrotiklibrary->checkThreshholds($device);
        }
    }
    public static function updateInterfaces(){
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $backhauls = \DB::SELECT('select 
locations.name as locationname,
backhauls.to_location_id,
interfaces.txspeed,
interfaces.rxspeed,
devices.id as device_id,
interfaces.name as interface_name,
interfaces.id as interface_id,
interfaces.updated_at,
interfaces.maxtxspeed,
interfaces.maxrxspeed,
backhaultypes.name,
interfaces.threshhold 
 from backhauls  
inner join interfaces on interfaces.id = backhauls.dinterface_id
inner join devices on devices.id = interfaces.device_id
inner join locations on backhauls.location_id = locations.id 
inner join backhaultypes on backhaultypes.id = backhauls.backhaultype_id');

        foreach ($backhauls as $backhaul){
            $vars =  "iname="."'".$backhaul->interface_name."'"." and host="."'".$backhaul->device_id."'";
            $query = "SELECT * FROM interfaces WHERE time > now() - 1h and ".$vars."   ORDER BY time DESC limit 1;";
            $stats = $db->query($query);
            if (isset($stats)){
                if(array_key_exists('0',$stats)){
                    $backhaul->rxspeed= $stats[0]->rxvalue;
                    $backhaul->txspeed= $stats[0]->txvalue;
                    \DB::statement('update interfaces set txspeed='.$stats[0]->txvalue." where interfaces.id=".$backhaul->interface_id);
                    echo 'update interfaces set txspeed='.$stats[0]->txvalue." where interfaces.id=".$backhaul->interface_id."\n";
                    \DB::statement('update interfaces set rxspeed='.$stats[0]->rxvalue." where interfaces.id=".$backhaul->interface_id);
                    echo 'update interfaces set rxspeed='.$stats[0]->rxvalue." where interfaces.id=".$backhaul->interface_id."\n";

                    echo $backhaul->interface_id."\n";
                }
            }
        }
    }
    public static function checkOneInterfaceThreshholds($device){
        $mikrotiklibrary = new MikrotikLibrary();
            $mikrotiklibrary->checkThreshholds($device);
    }
    public static function checkOneDeviceInterfaceThreshholds($id){
        $device = Device::find($id);
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->checkThreshholds($device);
    }
    public static function setHistoricalValue(){
        \DB::statement('DROP TABLE IF EXISTS old_dte.devices');
        \DB::statement('CREATE TABLE old_dte.devices LIKE devices');
        \DB::statement('INSERT old_dte.devices SELECT * FROM devices');
    }

    public static function graphInterfaces(){
        //\Log::info("Interface graphing job started");
        $devices = Device::where('devicetype_id',"1")->get();
        $themikrotiklibrary = new MikrotikLibrary();
        $themikrotiklibrary->graphAllInterfaces($devices);
    }
    public static function graphInterfacesByID($id){
        $device = Device::find($id);
        $themikrotiklibrary = new MikrotikLibrary();
        $ciscolibrary = new CiscoLibrary();
        if($device->devicetype_id == 1){
            $themikrotiklibrary->graphAllInterfacesbyDevice($device);
            $themikrotiklibrary->storeMikrotikDInterface($device);
        }
        if($device->devicetype_id == 7){
            $ciscolibrary->CalculateThroughput($device);
            $ciscolibrary->StoreInterfaces($device);
        }
        if($device->devicetype_id == 6){
            $ciscolibrary->CalculateThroughput($device);
            $ciscolibrary->StoreInterfaces($device);
        }
    }
    public static function getAllPPPoe(){
        $devices = Device::where('devicetype_id',"1")->get();
        $max = sizeof($devices);
        $count = 0;
        foreach ($devices as $device){
            $count++;
            $themikrotiklibrary = new MikrotikLibrary();
            echo $device->name." ".(round($count/$max * 100,2))."\% \n";
            $themikrotiklibrary->getPPPOEClients($device);
        }
    }
    public function getMikrotikDefaultGateway()
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->getMikrotikDefaultGateway($this);
    }
    public static function getAllMikrotikRoutes(){
        $devices = Device::where('devicetype_id','1')->get();
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->getAllMikrotikRoutes($devices);
    }
    public static function getAllMikrotikIps(){
        $mikrotiklibrary = new MikrotikLibrary();
        $API = new RouterosAPI();
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            //$mikrotiklibrary->getIPs($API,$device);
        }
    }
    public function getUbntInfo()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntInfo($this);
        $this->signal = $this->txsignal;
        if($this->signal < 1){
            $this->signal = 0;
        }
        $data = array(
            "host" => $this->id,
            "freq" => $this->freq,
            "txpower" => $this->txpower,
            "width" => $this->channel,
            "signal" => $this->signal,
            "noise_floor" => $this->noise_floor,
            "stations" => $this->active_stations
        );

        $this->save();
        $rrdFile = "/var/www/html/dte/rrd/ubnts/".trim($this->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:freq:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:width:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
                "DS:stations:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD " . $rrdFile."\n";
            if (!\rrd_create($rrdFile, $options)) {
                echo rrd_error();
            }
        } else {
            $time = time();
            //\Log::info("Updating RRD for $rrdFile at ".time());
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "freq" => $data["freq"],
                "txpower" => $data["txpower"],
                "width" => $data["width"],
                "signal" => $data["signal"],
                "stations" => $data["stations"],
                "noise_floor" => $data["noise_floor"],
            ), $time);
        }
//        try{
//            $ubntlibrary->StoreInterfaces($this);
//        }catch (\Exception $e){
//            echo $e;
//        }
//        try{
//            $ubntlibrary->CalculateThroughput($this);
//        }catch (\Exception $e){
//            echo $e;
//        }
//        try{
//            $ubntlibrary->syncInterfaces($this);
//        }catch (\Exception $e){
//            echo $e;
//        }
    }
    public function getConnections()
    {
        $ubntlibrary = new UbntLibrary();
        try{
            $ubntlibrary->getUbntWirelessStations($this);
        }catch (\Exception $e){

        }
    }
    public static function getDevicesSerialNumbers(){
        $themikrotiklibrary = new MikrotikLibrary();
        $theubntlibrary = new UbntLibrary();
        $devices = Device::get();
        foreach ($devices as $device){
            $device->getSerialNumber($themikrotiklibrary,$theubntlibrary);
        }
    }
    public function getSerialNumber($themikrotiklibrary,$theubntlibrary){

        if ($this->devicetype_id =="1"){
            $themikrotiklibrary->getSerialNumber($this);
        }
        if ($this->devicetype_id =="2" or $this->devicetype_id =="3" or $this->devicetype_id =="10" or $this->devicetype_id =="11" or $this->devicetype_id =="22" or  $this->devicetype_id =="24"){
            $themikrotiklibrary->getSerialNumber($this);
        }
    }

    public static function updateScheduledDevicesSoftware()
    {
        $devices = Device::where('sch_update', '=', '1')->get();

        foreach ($devices as $device) {
            $device->updateSoftware();
            $device->rebootDevice();
            $device->sch_update = '0';
            $device->save();
        }
    }
    public function updateSoftware()
    {
        if ($this->devicetype_id == "1") {
            $this->updateMikrotikSoftware();
        }
    }
    public function updateMikrotikSoftware()
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->updateMikrotikSoftware($this);
    }
    public function rebootDevice()
    {
        if ($this->devicetype_id == "1") {
            $this->rebootMikrotik();
        }
        return view('device.show', compact('device'));
    }
    public function rebootMikrotik()
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->rebootMikrotik($this);
    }
    public static function checkMikrotikBackup($device){
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->checkMikrotikBackup($device);
    }
    public static function backupMikrotiks($worker)
    {
        $devices = Device::where('devicetype_id','1')->get();
        $count = ($devices->count()/10);
        $chunks = $devices->chunk($count);
        foreach ($chunks[$worker] as $device){
            $device->backupMikrotik($device);
        }
    }
    public static function backupMikrotik($device)
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $filename = $mikrotiklibrary->backupMikrotik($device);
        return $filename;
    }
    public function getNeighbors($device)
    {
        $mikrotiklibrary = new MikrotikLibrary();
        try {
        $mikrotiklibrary->getAllIPNeighbors($device);
        }catch(\Exception $e){

        }
    }
    public function getbackupfile($device)
    {
        $date = date("Y-m-d");
        //dd($date);
        $local_file  = config('mikrotik.backup_storage') . "$device->ip.$date.rsc";
        $server_file = 'dte_backup.rsc';
    try{
        // set up basic connection
        $conn_id = ftp_connect($device->ip,$device->ftp_port);

        $login_result = ftp_login($conn_id, $device->md5_username, $device->md5_password);
        ftp_pasv($conn_id, true);

    }  catch (\Exception $e) {
            echo "There was a problem\n$e";
            \Session::flash('flash_message', 'Device Failed!');
        return "Error";
    }


        // login with username and password
            try {

                if(is_array(ftp_nlist($conn_id, ".")) ){
                    // try to download $server_file and save to $local_file
                if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
                    echo $device->name. " backup downloaded. \n";
                    $device->backed_up = 1;
                    $device->date_backed_up = $date;
                    $device->save();
                    //\Log::info("$device->id $device->name  Backed up successfully!!");
                } else {
                    echo "There was a problem\n";
                    //\Log::info("$device->id $device->name  Back up failed!!");

                    $device->backed_up = 0;
                    $device->date_backed_up = "old";
                    $device->save();

                    }
                }
            }catch (\Exception $e) {
                echo "Backup exception " . $e;
                \Session::flash('flash_message', 'Device Failed!');
                return "Error".$device->ip.$date;
            }
        // close the connection
        ftp_close($conn_id);
        return $device->ip.'.'.$date;
    }

}