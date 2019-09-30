<?php

namespace App;


use App\Ip;
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
use Pyrus//\Logger;
use App\Neighbor;
use App\Jacques\CambiumLibrary;


class OldDevice extends Model
{
    protected $fillable = ['ack_note','client_datatill_link','default_gateway_id','ping','ping2','ping3','ping1', 'acknowledged', 'ack_user_id', 'sch_update', 'ip', 'name', 'temp', 'cpu', 'total_memory', 'free_memory', 'active_pppoe', 'active_stations', 'avg_ccq', 'volts', 'current', 'soft', 'firm', 'ping', 'location_id', 'devicetype_id', 'created_at', 'updated_at','ssh_username','ssh_password'];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function neighbors(){
        return $this->hasMany('App\Neighbor');
    }


    public function ips()
    {
        return $this->hasMany('App\IP');
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



    //// ACKNOWLEDGEMENTS TODO: MOVE TO ACKNOWLEDGEMENTS
    //// ACKNOWLEDGEMENTS
    //// ACKNOWLEDGEMENTS
    public function getDownTimeToday(){
        return Notification::calculateDailyDowntime($this);
    }

    public function getDownTimeThisWeek(){
        return Notification::calculateWeeklyDowntime($this);
    }

    public function getDownTimeThisMonth(){
        return Notification::calculateMonthlyDowntime($this);
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

    public function getAcknowledgementNote(){
        $acknowledgement = Acknowledgement::where('device_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            return $acknowledgement->ack_note;
        }else return "";
    }

    //// ACKNOWLEDGEMENTS
    //// ACKNOWLEDGEMENTS
    //// ACKNOWLEDGEMENTS

    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//
    public function getASNDeviceName($AS)
    {
        $device = Device::where('as_number', '=', $AS)->first();
        if (isset($device->name)) {
            return $device->name;
        } else return "Unknown";
    }

    public static function checkAllPolling(){
        $devices = Device::get();

        foreach ($devices as $device){
            echo $device->ip."\n";
            $device->checkPolling();
        }
    }

    public function checkPolling(){
        if (
            ($this->devicetype_id=="3") or
            ($this->devicetype_id=="4") or
            ($this->devicetype_id=="5") or
            ($this->devicetype_id=="6") or
            ($this->devicetype_id=="7") or
            ($this->devicetype_id=="12") or
            ($this->devicetype_id=="13") or
            ($this->devicetype_id=="14") or
            ($this->devicetype_id=="16") or
            ($this->devicetype_id=="20") or
            ($this->devicetype_id=="21") or
            ($this->devicetype_id=="23") or
            ($this->devicetype_id=="24") or
            ($this->devicetype_id=="25")
        ){
            $this->pollstatus = 1;
            $this->save();
        }elseif ($this->devicetype_id=="1"){
            $this->testMikrotikPolling($this);
        }else{
            $this->testSnmpPolling($this);
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
            $this->pollstatus = 1;
            $this->save();
        }
    }


    public function getASNDeviceID($AS)
    {
        $device = Device::where('as_number', '=', $AS)->first();
        if (isset($device->name)) {
            return $device->id;
        } else return "Unknown";
    }

    public function getChildren(){
        $children = Device::where('default_gateway_id',$this->id)->get();
        return $children;
    }

    public function getIdFromIp($ip){
        $ip = trim($ip);
        $ip = Ip::where('address',$ip)->first();
        if (isset($ip->id)){
            return $ip->device_id;
        }else return 0;
    }

    public static function SpeedtestAll(){
        $devices = Device::get();
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $themikrotiklibrary->speedTest($device);
        }

    }


    public function getMinMaxInterfaces($device,$time){
        $influx = new InfluxLibrary();
        $query = "SELECT * FROM interfacesminmax where host="."'".$device->id."' and time > ".$time.";";
        return $interfaces = $influx->selectFromDB($query);
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

    public static function StoreAllDInterfaces(){
        $devices = Device::where('devicetype_id','1')->get();
        $mikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $mikrotiklibrary->storeMikrotikDInterface($device);
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

    public static function updatedeviceLocationsfromIP(){
        $devices = Device::with('location')->get();
        $names = array();
        foreach ($devices as $device) {
            echo $device->name. "\t-\t".$device->location->name."\n";

        }

    }



    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//
    //Name and ID LOOKUPS//


    /////////////////////////////////////Functions for background polling of all devices

    public static function update_all_snmp()
    {

        $devices = Device::where('poll','1')->get();
        //$devices = Device::where('processing', '!=', 1)->where('updated_at','<=',$formatted_date)->get();
        $devicescount = Device::count();
        $count        = 0;
        //\Log::info("-----------------   $devicescount Devices are going to be updated-----------------");

        foreach ($devices as $device) {
            ////\Log::info($device->ip . " will be updated");
        }

        $alreadybeingpolledtypes =  array('1','2');
        foreach ($devices as $device) {
            $exclude = 0;
            foreach ($alreadybeingpolledtypes as $value) {
                if ($device->devicetype_id == $value){
                    $exclude = 1;
                }
            }
            if($exclude != 1){
               // echo $device->name," ".$device->devicetype->name." not excluded \n";
                $device->updateDevice($device->id);
                $count++;
                $device->save();
            }
        }
        ////\Log::info("$count Devices ran through");
    }

    public static function ResetDownsToday()
    {
        $devices = Device::get();

        foreach ($devices as $device) {
            $device->seconds_down_today = 0;
            $device->seconds_down_today = 0;
            $device->downs_today = 0;
            $device->save();
        }
    }

    public static function updateDevice($id)
    {
        $nopoll = array("3","4","6", "7", "9", "12", "13", "14", "15", "16", "17");
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $device = Device::find($id);

        foreach ($nopoll as $devicetype) {
            if (($device->devicetype_id == $devicetype)){
//                $device->pollstatus = '1';
                $device->lastsnmpupdate = new \DateTime();
                $device->save();
            }
        }

        echo $device->id." ".$device->devicetype->name."\n";
        if ($device->devicetype_id == "1") {
            $device->getGenericMikrotik();
        }
        if ($device->devicetype_id == "26") {
            $device->getGenericMikrotik();
        }
        if ($device->devicetype_id == "2") {
            $device->getUbntSector();
        }
        if ($device->devicetype_id == "5") {
            $device->getSiaeRadio();
        }
        if ($device->devicetype_id == "22") {
            $device->getUbntACPrismSector();
        }
        if ($device->devicetype_id == "8") {
            $device->getLigowave();
        }
        if ($device->devicetype_id == "10") {
            $device->getUbntStation();
        }
        if ($device->devicetype_id == "11") {
            $device->getUbntAP();
        }
        if ($device->devicetype_id == "18") {
            $device->getGenericMikrotik();
        }
        if ($device->devicetype_id == "19") {
            $device->getligowaveRapidFire();
        }
        if ($device->devicetype_id == "20"){
            $device->getSmtpServer();
        }
        if ($device->devicetype_id == "15"){
            $device->getMikrotikWireless();
        }
        if ($device->devicetype_id =="17"){
            $device->updateCambium();
        }

    }

    public function updateCambium(){
        $cambiumlibrary = new CambiumLibrary();
        $cambiumlibrary->getCambiumWirelessStations($this);
        $cambiumlibrary->getCambiumDetails($this);
    }


    public static function PollSpesificDeviceTypes($devicetype){

        $name = Devicetype::find($devicetype);
        $devices = Device::where('devicetype_id',$name->id)->get();

        //\Log::info("--------------------------------------------------------------------------------------------");
        //\Log::info("-----------------------------------Polling all $name->name----------------------------------------");
        //\Log::info("-------------------------------------------".count($devices)."-----------------------------------------------");

        //\Log::info("--------------------------------------------------------------------------------------------");

        foreach ($devices as $device){
            $device->updateDevice($device->id);
        }
    }

    /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS

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

    function  getAveragePings($device)
    {
        $influx = new InfluxLibrary();
        $query = "SELECT * FROM pings where host ='".$device->ip."' order by time desc limit 500 ";
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
        dd($array);
    }

        /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS
    /////PINGS AND LATENCY FUNCTIONS

    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS
    public static function determineFizDeviceGateway(){

        $devices = Device::with('location')->get();
        foreach ($devices as $device){
            if ($device->location->site_type=="fiz"){
//                echo "Trying $device->name \n";
//                $ip_with_mask = $device->ip.'/29';
//                list($ip, $mask_int) = explode('/', $ip_with_mask);
//                $mask_nr = (pow(2, $mask_int) - 1) << (32 - $mask_int);
//                //pow(2, $x) - 1 changes the number to a number made of that many set bits
//                //and $y << (32 - $x) shifts it to the end of the 32 bits
//                $mask = long2ip($mask_nr);
//                $subnet_ip = long2ip(ip2long($ip) & $mask_nr);
//                $gateway_ip = long2ip((ip2long($ip) & $mask_nr) + 1);

                $ip = IP::where('address',$device->default_gateway)->first();
                if (isset($ip->address)){
                    $device->default_gateway = $device->default_gateway;
                    $device->default_gateway_id = $ip->device_id;
                    $device->save();
                }else{
                    echo $device->name." default gateway not found $device->default_gateway\n";
                    $device->default_gateway = $device->default_gateway;
                    $device->default_gateway_id = '2593';
                    $device->save();
                }
            }else{

            }
        }
    }
    public function getSMTPServerQueue($id){
        $smtp = new SmtpLibrary();
        $device = Device::find($id);
        $queue = $smtp->getQueue($device->ip);
        return $queue;
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

    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS
    /////SMTP FUNCTIONS

    public function getLigowave(){
        $this->ip = trim($this->ip);

        try {
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
//            $this->pollstatus = 1;
            $this->save();
        } catch (\Exception $e) {
            echo "Ligowave try catch failed with " . $e;
//            $this->pollstatus = 0;
            $this->save();
            return;
        }
    }

    public function getLigowaveRapidFire(){
        $this->ip = trim($this->ip);
        try {
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
            echo "Ligowave try catch failed with " . $e;
//            $this->pollstatus = 0;
            $this->save();
            return;
        }
    }



    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////


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

    public static function checkInterfaceThreshholds(){
        //\DB::table('interfaces_on_threshhold')->where('id', '>', 0)->delete();

        $mikrotiklibrary = new MikrotikLibrary();
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $mikrotiklibrary->checkThreshholds($device);
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

    public function getDinterfaceID($name,$id){
        $dinterface = DInterface::where('name',$name)->where('device_id',$id)->first();
        if (isset($dinterface->id)){
            return $dinterface->id;
        }else{
            return "";
        }

    }

    public static function setHistoricalValue(){
        \DB::statement('DROP TABLE IF EXISTS old_dte.old_devices');
        \DB::statement('CREATE TABLE old_dte.old_devices LIKE devices');
        \DB::statement('INSERT old_dte.old_devices SELECT * FROM devices');
    }


    public function getMikrotikWireless(){
        $mikrotiklibrary = new MikrotikLibrary();
        $device = $this;
        $mikrotiklibrary->updateMikrotik($device);
        $mikrotiklibrary->getMikrotikWireless($device);
        $device->save();
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
        $themikrotiklibrary->graphAllInterfacesbyDevice($device);
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

    public function getGenericMikrotik()
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $mikrotiklibrary->updateMikrotik($this);
    }

    public static function getAllMikrotikIps(){
        $mikrotiklibrary = new MikrotikLibrary();
        $API = new RouterosAPI();
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $mikrotiklibrary->getIPs($device,$API);
        }
    }
    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////
    ////// Mikrotik Section ///////


    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    public function getUbntStation()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntStation($this);
    }

    public function getUbntAP()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntAP($this);
    }

    public static function getUbntToughswitch()
    {

    }

    public function getUbntSector()
    {


        if ($this->ping = "1") {
            $this->active_stations = "0";
            $this->getConnections();
            $this->getUbntInfo();
            if ($this->active_stations != "0") {
                $this->lastsnmpupdate = new \DateTime();
            }
            $this->save();
            ////\Log::info("$this->id $this->name  Updated");


        } else {
            //\Log::info("$this->id $this->name  Failed");
            $this->save();
            //\Log::info("$this->id $this->name  Failed");
        }

    }

    public function getUbntACPrismSector()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntACPrismSector($this);
    }

    public function getUbntInfo()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntInfo($this);
    }

    public function getConnections()
    {
        $ubntlibrary = new UbntLibrary();
        $ubntlibrary->getUbntWirelessStations($this);
    }

    public function getSiaeRadio(){

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


    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////
    ////// UBNT SECTION ///////

    //update software functions
    //update software functions
    //update software functions
    //update software functions

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


    public static function backupMikrotiks()
    {
        //$device = Device::find("1462");
        $devices = Device::where('devicetype_id', '=', '1')->get();
        foreach ($devices as $device) {
            $device->backupMikrotik($device);
        }
    }

    public static function backupMikrotik($device)
    {
        $mikrotiklibrary = new MikrotikLibrary();
        $filename = $mikrotiklibrary->backupMikrotik($device);
        return $filename;
    }

    public static function getAllIPNeigbors(){
        $devices = Device::where('devicetype_id','1')->get();
        $themikrotiklibrary = new MikrotikLibrary();
        foreach($devices as $device){
            $themikrotiklibrary->getAllIPNeighbors($device);
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
        $conn_id = ftp_connect($device->ip);

        $login_result = ftp_login($conn_id, config('mikrotik.api_username'), config('mikrotik.api_password'));
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

    //update software functions
    //update software functions
    //update software functions
    //update software functions


    public static function getAllMikrotikInterfaces(){
        $mikrotiklibrary = new MikrotikLibrary();
        return $mikrotiklibrary->getAllMikrotikinterfaces();
    }

    public static function getHighestMikrotikInterfaces(){
        $mikrotiklibrary = new MikrotikLibrary();
        return $mikrotiklibrary->getAllMikrotikinterfaces();
    }


}