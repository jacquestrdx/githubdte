<?php

namespace App\Jacques;

use App\Client;
use App\DInterface;
use App\Fault;
use App\Interfacelog;
use App\Ip;
use App\User;
use App\Supa;
use App\Neighbor;
use App\Jacques\Mailer;
use App\BGPPeer;
use App\Ghost;
use App\InterfaceWarning;
use App\Device;
use App\Statable;
use App\Pppoeclient;
use App\Acknowledgement;
use App\Deviceinterface;
use App\HistoricalPingWorker;
use App\Jacques\InfluxLibrary;
use App\Jacques\MacVendorsApi;
use App\Warning;

class MikrotikLibrary
{

    public function testLogin($device){
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
            $device->pollstatus = 1;
            $device->save();
        }else{
            $device->pollstatus = 0;
            $device->save();
        }
    }

    public function graphClientPPPOES(){
        MikrotikLibrary::graphAllPPPOES();
    }
    public static function graphAllPPPOES()
    {
        $devices = Device::where('devicetype_id', '1')->get();
        foreach ($devices as $device) {
            $themikrotiklibrary = new MikrotikLibrary();
            $interfaces = $themikrotiklibrary->getMikrotikPPPOES($device);
            if (isset($interfaces)) {
                if ($interfaces != "No-response") {
                    foreach ($interfaces as $interface) {
                        if (array_key_exists('name', $interface)) {
                            $username = preg_replace("/(\<|\>)/", "", $interface['name']);
                            $username = strtolower(preg_replace("/pppoe-/", "", $username));
                            if (Client::where('username',  $username)->exists()) {
                                $client = Client::where('username', $username)->first();
                                $host = $client->id;
                                $txvalue = round($interface['tx_speed'] / 1024 / 1024, 2);
                                $rxvalue = round($interface['rx_speed'] / 1024 / 1024, 2);
                                $time = time();
                                $data = array(
                                    "host" => $host,
                                    "txvalue" => $txvalue,
                                    "rxvalue" => $rxvalue,
                                    "iname" => $username
                                );
                                $value = 1;
                                //InfluxLibrary::writeToDB("dte", "clientinterfaces", $data, $value);
                                echo $username . " has been insterted from " . $client->username . "\n";
                            }
                        }
                    }
                }
            }
        }
    }

    public function getMikrotikPPPOES($device){
        $interfaces = array();
        try{
            echo "Starting $device->name \n";
            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/interface/print');
                    $READ       = $API->read();
                    echo "FOUND ".sizeof($READ)." PPPOES \n";

                    foreach ($READ as $key => $row){

                        if ( ($row['type'] == "pppoe-in") or ($row['type'] == "l2tp-in")){
                            $interfaces[$key] = $row;
                            $name = $row['name'];
                            if($row['running']=="true"){
                                $API->write("/interface/monitor-traffic",false);
                                $API->write("=interface=".$name,false);
                                $API->write("=once=",true);
                                $interfacestats = $API->read();
                                if (array_key_exists('0',$interfacestats)){
                                    $interfaces[$key]['tx-speed'] = $interfacestats['0']['tx-bits-per-second'] ?? $interfaces[$key]['tx-speed'] = "";
                                    $interfaces[$key]['rx-speed'] = $interfacestats['0']['rx-bits-per-second'] ?? $interfaces[$key]['rx-speed'] = "";
                                    $results[] = array(
                                        "name" => $name,
                                        "tx_speed" => $interfaces[$key]['tx-speed'],
                                        "rx_speed" => $interfaces[$key]['rx-speed']
                                    );
                                    echo $name. " - ".$interfaces[$key]['tx-speed']. " / ".$interfaces[$key]['rx-speed']."\n";
                                }
                            }else{

                            }
                        }
                    }
                }else{
                    return $results = "No-response";
                }
            }else{
                return  $results = "No-response";
            }
        }catch (\Exception $e){
            return  $results = "No-response";
        }
        echo "Made it to the end $device->name\n";
        if (isset($results)){
            return $results;
        }else{
            return  $results = "No-response";
        }
    }

    public function changeInterimUpdate($device){
        echo "Trying $device->name \n";
        $API = new RouterosAPI();
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
            $API->write('/ppp/aaa/set',false);
            $API->write('=interim-update=120m');
            $READ = $API->read();
            $API->write('/radius/print');
            $READ = $API->read();
            foreach($READ as $row){
                $API->write('/radius/set',false);
                $API->write('=timeout=3000ms',false);
                $API->write('=.id='.$row[".id"], true);
                $array = $API->read();
            }
            echo "Success $device->name \n";
        }else{
            echo "FAILED $device->name \n";
        }

    }

    public static function changeAllInterimUpdate(){
        $themikrotiklibrary = new MikrotikLibrary();
        $devices = Device::where('devicetype_id','1')->get();
        foreach($devices as $device){
            $themikrotiklibrary->changeInterimUpdate($device);
        }
    }

    public function getAllMikrotikinterfaces(){
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $query = "SELECT * FROM interfaces WHERE time > now() - 2h  ORDER BY time DESC;";
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
                $array[$stat->host][$stat->iname] = array(
                    "time" => $stat->time,
                    "rxvalue" => $stat->rxvalue,
                    "txvalue" => $stat->txvalue
                );
            }
        }

        return $array;
    }

    public function getOneMikrotikinterfaces($device)
    {
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
///
        $query = 'SELECT * FROM interfaces WHERE time > now() - 4h and host=' . "'" . $device->id . "';";
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
                $array[$stat->iname][] = array(
                    "time" => $stat->time,
                    "rxvalue" => $stat->rxvalue,
                    "txvalue" => $stat->txvalue
                );
            }
        }
        if (isset($array)) {
            foreach ($array as $interface => $values) {
                $maxtx = 0;
                $maxrx = 0;
                $maxtxtime = "";
                $maxrxtime = "";
                foreach ($values as $value) {
                    if ($value['txvalue'] > $maxtx) {
                        $maxtx = $value['txvalue'];
                        $maxtxtime = $value['time'];
                    }
                    if ($value['rxvalue'] > $maxrx) {
                        $maxrx = $value['rxvalue'];
                        $maxrxtime = $value['time'];
                    }
                }
                $results[$interface] = array(
                    "maxtx" => $maxtx,
                    "maxrx" => $maxrx,
                    "maxtxtime" => $maxtxtime,
                    "maxrxtime" => $maxrxtime
                );
            }
        }
        if (isset($results)){
            return $results;
        }else{
            return "";
        }

    }

    public function getSystemIdentity($API, $device)
    {
        echo "getSystemIdentity \n";
        $time = time();
        $API->write('/system/identity/print');
        $READ = $API->read();
        $time2 = time();
        echo ($time2-$time)."\n";
        if (array_key_exists("0",$READ)){
            return $READ[0]['name'];
        }else{
            return "System identity not found";
        }
    }

    public function getTheMikrotikWireless($device){
        echo "Getting Wireless \n";
        $API = new RouterosAPI();
        $API->debug = false;
        try{
            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)){
                $API->write('/interface/wireless/print');
                $READ = $API->read();
                if(array_key_exists('0',$READ)){
                    if (array_key_exists("interface-type",$READ['0'])){
                        $device->model = $READ['0']['interface-type'];
                    }
                    if (array_key_exists("mode",$READ['0'])){
                        $device->wireless_mode = $READ['0']['mode'];
                    }
                    if (array_key_exists("ssid",$READ['0'])){
                        $device->ssid = $READ['0']['ssid'];
                    }
                    if (array_key_exists("frequency",$READ['0'])){
                        $device->freq = $READ['0']['frequency'];
                    }
                    if (array_key_exists("channel-width",$READ['0'])){
                        $device->channel = $READ['0']['channel-width'];
                        $device->channel = preg_replace("/mhz/", "",  $device->channel);
                    }
                }
                $themikrotiklibrary = new MikrotikLibrary();
                if ($device->wireless_mode == "ap-bridge"){
                    try{
                        $themikrotiklibrary->getMikrotikWirelessStations($device);
                    }catch (\Exception $e){
                    }
                }
                $device->active_stations = sizeof($device->statables);
            }
            $device->save();
        }catch (\Exception $e){
        }
    }

    public function getMikrotikWirelessStations($device){
        $API = new RouterosAPI();
        echo "Doing Wireless \n";
        $API->debug = false;
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
            $API->write('/interface/wireless/registration-table/print');
            $READ = $API->read();
//    "interface" => "wlan1"
//    "mac-address" => "58:63:56:8F:C4:42"
//    "rx-rate" => "72.2Mbps-20MHz/1S/SGI"
//    "tx-rate" => "72.2Mbps-20MHz/1S/SGI"
//    "uptime" => "1w1h22m58s"
//    "signal-strength" => "-52@1Mbps"
//    "distance" => "2"
//    "last-ip" => "192.168.1.9"
            foreach ($READ as $mac){
                if (Statable::where('mac', '=', $mac['mac-address'])->exists()) {
                    $statable = Statable::where('mac', '=', $mac['mac-address'])->first();
                    $statable->mac = $mac['mac-address'] ?? $statable->mac = "N/A";
                    if (array_key_exists('radio-name',$mac)){
                        $statable->name = $mac['radio-name'];
                    }else{
                        $statable->name = "N/A";
                    }
                    if (array_key_exists('last-ip',$mac)){
                        $statable->ip = $mac['last-ip'];
                    }else{
                        $statable->ip = "N/A";
                    }
                    if (array_key_exists('last-ip',$mac)){
                        $statable->latency = "N/A";
                    }else{
                        $statable->latency = "N/A";
                    }
                    if (array_key_exists('tx-ccq',$mac)){
                        $statable->ccq = $mac['tx-ccq'] ;
                    }else{
                        $statable->ccq = "N/A";
                    }
                    if (array_key_exists('signal-strength',$mac)){
                        $signal = preg_split("/@/",$mac['signal-strength']) ;
                        $statable->signal = $signal['0'];
                    }else{
                        $statable->signal = "N/A";
                    }
                    if (array_key_exists('uptime',$mac)){
                        $statable->time = $mac['uptime'];
                    }else{
                        $statable->time = "N/A";
                    }
                    if (array_key_exists('rx-rate',$mac)){
                        $rxrate =  preg_split("/Mbps/", $mac['rx-rate']);

                    }
                    if (array_key_exists('tx-rate',$mac)){
                        $txrate =  preg_split("/Mbps/", $mac['tx-rate']);
                    }
                    $rxrate = $rxrate['0'];
                    $txrate = $txrate['0'];

                    if (array_key_exists('signal-strength',$mac)){
                        $statable->rates = $txrate."/".$rxrate;
                    }else{
                        $statable->rates = "N/A";
                    }
                    $statable->device_id = $device->id;
                    $statable->save();
                } else {
                    $statable = new Statable;
                    $statable->mac = $mac['mac-address'] ?? $statable->mac = "N/A";
                    if (array_key_exists('radio-name',$mac)){
                        $statable->name = $mac['radio-name'];
                    }else{
                        $statable->name = "N/A";
                    }
                    if (array_key_exists('last-ip',$mac)){
                        $statable->ip = $mac['last-ip'];
                    }else{
                        $statable->ip = "N/A";
                    }
                    if (array_key_exists('last-ip',$mac)){
                        $statable->latency = "N/A";
                    }else{
                        $statable->latency = "N/A";
                    }
                    if (array_key_exists('tx-ccq',$mac)){
                        $statable->ccq = $mac['tx-ccq'] ;
                    }else{
                        $statable->ccq = "N/A";
                    }
                    if (array_key_exists('signal-strength',$mac)){
                        $signal = preg_split("/@/",$mac['signal-strength']) ;
                        $statable->signal = $signal['0'];
                    }else{
                        $statable->signal = "N/A";
                    }
                    if (array_key_exists('uptime',$mac)){
                        $statable->time = $mac['uptime'];
                    }else{
                        $statable->time = "N/A";
                    }

                    if (array_key_exists('rx-rate',$mac)){
                        $rxrate =  preg_split("/Mbps/", $mac['rx-rate']);
                    }
                    if (array_key_exists('tx-rate',$mac)){
                        $txrate =  preg_split("/Mbps/", $mac['tx-rate']);
                    }
                    $rxrate = $rxrate['0'];
                    $txrate = $txrate['0'];

                    if (array_key_exists('signal-strength',$mac)){
                        $statable->rates = $txrate."/".$rxrate;
                    }else{
                        $statable->rates = "N/A";
                    }
                    $statable->device_id = $device->id;
                    $statable->save();
                }
            }
        }
    }

    public function getDnsServer($API, $device)
    {
        echo "getDnsServer \n";
        $API->write('/ip/dns/print');
        $READ = $API->read();
        if (array_key_exists("0",$READ)){
            return $READ[0]['servers'];
        }else{
            return "An Error occurred";
        }
    }

    public function getPPTPServer($API, $device)
    {
        echo "getPPTPServer \n";
        $API->write('/interface/pptp-server/server/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            if ($READ['0']['enabled'] == "true") {
                return "1";
            } else {
                return "0";
            }
        }
    }

    public function getSSTPServer($API, $device)
    {
        echo "getSSTPServer \n";
        $API->write('/interface/sstp-server/server/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            if ($READ['0']['enabled'] == "true") {
                return "1";
            } else {
                return "0";
            }
        }
    }

    function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    public function getL2TPServer($API, $device)
    {
        echo "getL2TPServer \n";
        $API->write('/interface/l2tp-server/server/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            if ($READ['0']['enabled'] == "true") {
                return "1";
            } else {
                return "0";
            }
        }
    }

    public function getOVPNServer($API, $device)
    {
        echo "getOVPNServer \n";
        $API->write('/interface/ovpn-server/server/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            if ($READ['0']['enabled'] == "true") {
                return "1";
            } else {
                return "0";
            }
        }
    }

    public function getSystemResources($API, $device)
    {
        echo "getSystemResources \n";

        $API->write('/system/resource/print');

        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            $cpu = $READ[0]['cpu-load'];
            $uptime = $READ[0]['uptime'];
            $totalmem = $READ[0]['total-memory'];
            $freem = $READ[0]['free-memory'];
            $boardname = $READ[0]['board-name'];
            $software = preg_replace("/[^0-9,.]/", "", $READ[0]['version']);
            $usedmem = (($totalmem) - ($freem));
            $usedmem = (($usedmem / $totalmem) * 100);
            $data = array(
                "cpu" => $cpu,
                "total_memory" => $totalmem,
                "free_memory" => $freem,
                "model" => $boardname,
                "uptime" => $uptime,
                "soft" => $software,
                "used_memory" => $usedmem,
            );
        }else{
            $data = array(
                "cpu" => "no Value",
                "total_memory" => "no Value",
                "free_memory" => "no Value",
                "model" => "no Value",
                "uptime" => "no Value",
                "soft" => "no Value",
                "used_memory" => "no Value",
            );
        }
        return $data;
    }

    public function getIPs($device)
    {
        $API = new RouterosAPI();
        $API->debug = false;
        try {

            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                $API->write('/ip/address/print');

                $READ = $API->read();
                foreach ($READ as $row) {
                    $address = $row['address'] ?? $address = "n/a";
                    $ip = new Ip;
                    $ipaddress = preg_split("/\//", $address);
                    if ((array_key_exists('0', $ipaddress)) AND (array_key_exists('1', $ipaddress))) {
                        if (!IP::where('address', '=', $ipaddress['0'])->where('device_id', '=', $device->id)->exists()) {
                            $ip->address = $ipaddress['0'];
                            $ip->device_id = $device->id;
                            $ip->save();
                            echo "Creating new IP " . $ipaddress['0'] . "\n";
                        } else {
                            $ip = IP::where('address', '=', $ipaddress['0'])->first();
                            $ip->address = $ipaddress['0'];
                            $ip->device_id = $device->id;
                            $ip->save();
                            echo "Updating IP " . $ipaddress['0'] . "\n";

                        }
                    }
                }
            }
        }catch (\Exception $e){

        }
    }

    public function getExternalIPs($device)
    {
        $count = 0;
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
//            echo "getIPs for $device->ip\n";
            $API->write('/ip/address/print');

            $READ = $API->read();

            foreach ($READ as $row) {
                $address = $row['address'] ?? $ip = "n/a";
                $ip = new Ip;
                $ipaddress = preg_split("/\//", $address);
                if ($ipaddress['1'] != "32") {
                    if (!IP::where('address', '=', $ipaddress['0'])->exists()) {
                        $ip->address = $ipaddress['0'];
                        $ip->device_id = $device->id;
                        $ip->save();
                        $count++;
//                        echo "Creating new IP " . $ipaddress['0'] . "\n";
                    } else {
                        $ip = IP::where('address', '=', $ipaddress['0'])->first();
                        $ip->address = $ipaddress['0'];
                        $ip->device_id = $device->id;
                        $ip->save();
                        $count++;

//                        echo "Updating IP " . $ipaddress['0'] . "\n";

                    }
                }
            }
        }
        if ($count ==0){
            echo "No IPS FOR $device->name - $device->ip \n";
        }
    }

    public function getSystemHealth($API, $device)
    {
        echo "getSystemHealth \n";
        $API->write('/system/health/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            if ($READ['0'] != null) {
                echo "1st Pass \n";
                if (array_key_exists('voltage', $READ[0])) {
                    echo "2nd Pass \n";
                    $volts = $READ[0]['voltage'];
                } else {
                    $volts = "0";
                }
                if (array_key_exists('current', $READ[0])) {
                    echo "3nd Pass \n";

                    $current = $READ[0]['current'];
                } else {
                    $current = "0";
                }
                if (array_key_exists('psu1-state', $READ[0])) {
                    if($READ[0]['psu1-state']=="ok"){
                        $psu1state = 1;
                    }else{
                        $psu1state = 0;
                    }
                } else {
                    $psu1state = 1;
                }
                if (array_key_exists('psu2-state', $READ[0])) {
                    if($READ[0]['psu2-state']=="ok"){
                        $psu2state = 1;
                    }else{
                        $psu2state = 0;
                    }
                } else {
                    $psu2state = 1;
                }

                if (array_key_exists('temperature', $READ[0])) {
                    echo "Final Pass \n";

                    $temperature = $READ[0]['temperature'];
                } else {
                    $temperature = "0";
                }
                $data = array(
                    "volts" => $volts,
                    "current" => $current,
                    "temperature" => $temperature,
                    "psu1state" => $psu1state,
                    "psu2state" => $psu2state
                );
            } else {
                $current = "0";
                $temperature = "0";
                $volts = "0";
                $psu1state = "1";
                $psu2state = "1";
                $data = array(
                    "volts" => $volts,
                    "current" => $current,
                    "temperature" => $temperature,
                    "psu1state" => $psu1state,
                    "psu2state" => $psu2state
                );
            }
        } else {
            $current = "0";
            $temperature = "0";
            $volts = "0";
            $psu1state = "1";
            $psu2state = "1";
            $data = array(
                "volts" => $volts,
                "current" => $current,
                "temperature" => $temperature,
                "psu1state" => $psu1state,
                "psu2state" => $psu2state
            );
        }
        return $data;
    }

    public function getSystemRouterboard($API, $device)
    {
        echo "getSystemRouterboard \n";
        $API->write('/system/routerboard/print');
        $READ = $API->read();
        if (array_key_exists('0', $READ)) {
            return $READ[0]['current-firmware'];
        }
    }

    public function getActiveHotspot($API,$device){
        $API->write('/ip/hotspot/active/print');
        $READ = $API->read();
        $max_active_hotspot	 = $device->max_active_hotspot	;
        if (array_key_exists('0', $READ)) {
            $active_hotspot = count($READ) ?? $active_hotspot = 0;
            if ($device->max_active_hotspot <= $active_hotspot) {
                $max_active_hotspot = $active_hotspot;
            }
        } else {
            echo "Hotspot NOT FOUND \n";
            $active_hotspot = 0;
        }
        $data = array(
            "active_hotspot" => $active_hotspot,
            "max_active_hotspot" => $max_active_hotspot
        );
        return $data;
    }

    public function getActivePPP($API, $device)
    {
        echo "getActivePPP \n";
        $API->write('/ppp/active/print');
        $READ = $API->read();
        $maxactivepppoe = $device->maxactivepppoe;
        if (array_key_exists('0', $READ)) {
            $active_pppoe = count($READ) ?? $active_pppoe = 0;
            if ($device->maxactivepppoe <= $active_pppoe) {
                $maxactivepppoe = $active_pppoe;
            }
        } else {
            echo "PPP NOT FOUND \n";
            $active_pppoe = 0;
        }
        $data = array(
            "active_pppoe" => $active_pppoe,
            "maxactivepppoe" => $maxactivepppoe
        );

        return $data;
    }

    public static function getAllPPPoeVendors(){
        $MacVendorApi = new MacVendorsApi();
        $pppoeclients = Pppoeclient::get();

        foreach ($pppoeclients as $pppoeclient){
            if (($pppoeclient->is_online != "0")){
                echo $pppoeclient['username']." ".$pppoeclient->device->name." "."\n";
                $vendor = $MacVendorApi->get_vendor("$pppoeclient->mac",'csv');
                $pppoeclient->vendor = $vendor['company'];
                $pppoeclient->save();
            }
//
        }
    }

    public static function getIPNeighbours($API,$device)
    {
        $API->write('/interface/getall');
        $READ = $API->read();

//
//        foreach ($READ as $result){
//            $API->write('/ip/neighbor/discovery/set', false);
//            $API->write('=discover=yes', false);
//            $API->write('=.id='.$result['.id'], true);
//            $READ = $API->read();
//        }
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {

            $API->write('/ip/neighbor/print', true);
            $READ = $API->read();
            foreach($READ as $result){
                if (array_key_exists('mac-address',$result)){
                    if (Neighbor::where('mac_address', '=', $result['mac-address'])->exists()) {
                        $neighbor = Neighbor::where('mac_address',$result['mac-address'])->first();
                        $datetime = date_create()->format('Y-m-d H:i:s');
                        $neighbor->mac_address = $result['mac-address'];
                        $neighbor->interface = $result['interface'];
                        $neighbor->ip = $result['address4'] ?? $neighbor->ip = "";
                        $neighbor->device_id = $device->id;
                        $neighbor->identity = $result['identity'] ?? $neighbor->identity = "";
                        $neighbor->platform = $result['platform'] ?? $neighbor->platform = "";
                        $neighbor->save();
                    }else{
                        $neighbor = new Neighbor();
                        $datetime = date_create()->format('Y-m-d H:i:s');
                        $neighbor->mac_address = $result['mac-address'];
                        $neighbor->ip = $result['address4'] ?? $neighbor->ip = "";
                        $neighbor->device_id = $device->id;
                        $neighbor->interface = $result['interface'];
                        $neighbor->identity = $result['identity'] ?? $neighbor->identity = "";
                        $neighbor->platform = $result['platform'] ?? $neighbor->platform = "";
                        $neighbor->save();
                    }
                }
            }
        }
    }
    //

    public static function getMikrotikInterfaceNames(){
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device) {
            try {
                if ($device->ping == "1") {
                    $API = new RouterosAPI();
                    $API->debug = false;
                    if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                        $API->write('/interface/print');
                        $READ = $API->read();
                        foreach ($READ as $interface) {
                            if ( ($interface['type'] != 'pppoe-in') AND ($interface['type'] != 'l2tp-in')){
                                $interface = new DInterface();
                                $interface->device_id = $device->id;
                                $interface->default_name = $interface['default-name'];
                                $interface->name = $interface['name'];
                                $interface->save();
                            }
                        }
                    }
                }
            } catch (\Exception $e){

            }
        }
    }

    public function checkThreshholds($device)
    {
        try {
        $date        = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $interfaces = DInterface::where('device_id', $device->id)->where('type', '!=', "pptp-in")->get();
        $count =0;
        foreach ($interfaces as $interface) {
            $count++;

            $array = array();
            $labels = array();
            $finals = array();
            $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($interface->device_id) . "/" . trim($interface->id) . ".rrd";
            $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time() - 2000), "--end", time() - 200));
            if (isset($result)) {
                foreach ($result["data"]["rxvalue"] as $key => $value) {
                    if (!is_finite($value)) {
                    } else {
                        $array['rxvalue'][] = $value;
                        $labels[] = $key;
                    }
                }
                foreach ($result["data"]["txvalue"] as $key => $value) {
                    if (!is_finite($value)) {
                    } else {
                        $array['txvalue'][] = $value;
                    }
                }
                foreach ($labels as $key => $value) {
                    if (isset($labels[$key + 1])) {
                        $array['timestamps'][] = $labels[$key + 1] - $value;
                    }
                }
                foreach ($array['rxvalue'] as $key => $value) {
                    if (isset($array['rxvalue'][$key + 1])) {
                        if( ($array['rxvalue'][$key + 1] ==0) or ($value ==0)){
                            $finals['rxvalue'][] = 0;
                        }else{
                            $rxvalue = $array['rxvalue'][$key + 1] - $value;
                            $final = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                            $finals['rxvalue'][] = $final;

                        }
                    }
                }

                foreach ($array['txvalue'] as $key => $value) {
                    if (isset($array['txvalue'][$key + 1])) {
                        if( ($array['txvalue'][$key + 1] == 0) or ($value == 0)){
                            $finals['txvalue'][] = 0;
                        }else{
                            $rxvalue = $array['txvalue'][$key + 1] - $value;
                            $finals['txvalue'][] = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                        }
                    }
                }
                foreach ($finals['txvalue'] as $key => $value) {
                    if ($key < sizeof($finals['txvalue']) - 1) {
                        $txvalue = $value;
                    }
                }
                foreach ($finals['rxvalue'] as $key => $value) {
                    if ($key < sizeof($finals['rxvalue']) - 1) {
                        $rxvalue = $value;
                    }
                }

                if ($interface->maxtxspeed < $txvalue) {
                    $interface->maxtxspeed = $txvalue;
                }
                if ($interface->maxrxspeed < $rxvalue) {
                    $interface->maxrxspeed = $rxvalue;
                }
                $interface->txspeed = $txvalue;
                $interface->rxspeed = $rxvalue;
                $interface->save();
                if ($interface->created_at < $formatted_date) {
                    echo $interface->device->name . " -- " .$interface->name. " using " . $interface->txspeed . " out of " . $interface->threshhold . "\n";
                    echo $interface->device->name . " -- " . " using " . $interface->rxspeed . " out of " . $interface->threshhold . "\n";
                    if ($interface->txspeed > ($interface->threshhold * 0.8)) {
                        $message = "$interface->name is running close to TX threshold of $interface->threshhold Mbps";
                        if (!InterfaceWarning::where('message', '=', $message)->exists()) {
                            $interfacewarning = new InterfaceWarning;
                            $interfacewarning->dinterface_id = $interface->id;
                            $interfacewarning->message = $message;
                            $interfacewarning->threshold = $interface->txspeed;
                            $interfacewarning->time = new \DateTime();
                            $interfacewarning->save();
                        } else {
                            InterfaceWarning::where('message', '=', $message)->first();
                            $interfacewarning = new InterfaceWarning;
                            $interfacewarning->dinterface_id = $interface->id;
                            $interfacewarning->message = $message;
                            $interfacewarning->threshold = $interface->txspeed;
                            $interfacewarning->time = new \DateTime();
                            $interfacewarning->save();
                        }
                    }

                    if ($interface->rxspeed > ($interface->threshhold * 0.8)) {
                        $message = "$interface->name is running close to RX threshold of $interface->threshhold Mbps";
                        if (!InterfaceWarning::where('message', '=', $message)->exists()) {
                            $interfacewarning = new InterfaceWarning;
                            $interfacewarning->dinterface_id = $interface->id;
                            $interfacewarning->message = $message;
                            $interfacewarning->threshold = $interface->rxspeed;
                            $interfacewarning->time = new \DateTime();
                            $interfacewarning->save();
                        } else {
                            $interfacewarning = InterfaceWarning::where('message', '=', $message)->first();
                            $interfacewarning->dinterface_id = $interface->id;
                            $interfacewarning->message = $message;
                            $interfacewarning->threshold = $interface->rxspeed;
                            $interfacewarning->time = new \DateTime();
                            $interfacewarning->save();
                        }
                    }
                }
            }
        }
            }catch (\Exception $e){

            }
    }

    public function getMikrotikInterfacesLive($device)
    {
        $interfaces = array();
        try {
            if ($device->ping == "1") {
                $API = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/interface/print');
                    $READ = $API->read();
                    foreach ($READ as $key => $row) {
                        if (($row['type'] != "pppoe-in") and ($row['type'] != "l2tp-in")) {
                            if (array_key_exists('default-name',$row)){
                                if (array_key_exists('comment',$row)){
                                    $interfaces[] = array(
                                        "name" => $row['name'],
                                        "default_name" => $row['default-name'],
                                        "comment" => $row['comment'],
                                    );
                                }else{
                                    $interfaces[] = array(
                                        "name" => $row['name'],
                                        "default_name" => $row['default-name'],
                                        "comment" => "none",
                                    );
                                }
                            }else{
                                if (array_key_exists('comment',$row)) {
                                    $interfaces[] = array(
                                        "name" => $row['name'],
                                        "default_name" => $row['name'],
                                        "comment" => $row['name'],
                                    );
                                }else{
                                    $interfaces[] = array(
                                        "name" => $row['name'],
                                        "default_name" => $row['name'],
                                        "comment" => "none",
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }catch(\Exception $e){
        }
        return $interfaces;
    }

    public function getOneBackhaulInterface($device)
    {
//        if ($device->ping == "1") {
//            try {
//
//                $API = new RouterosAPI();
//                $API->debug = false;
//                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
//                    $API->write('/interface/print');
//                    $READ = $API->read();
//                    foreach ($READ as $key => $row) {
//                        if (($row['type'] != "pppoe-in") and ($row['type'] != "l2tp-in")) {
//                            $interfaces[$key] = $row;
//                            $name = $row['name'];
//                            if ($row['running'] == "true") {
//                                $API->write("/interface/monitor-traffic", false);
//                                $API->write("=interface=" . $name, false);
//                                $API->write("=once=", true);
//                                $interfacestats = $API->read();
//                                if (array_key_exists('0', $interfacestats)) {
//                                    $interfaces[$key]['tx_speed'] = ($interfacestats['0']['tx-bits-per-second'] / 1024 / 1024) ?? $interfaces[$key]['tx-speed'] = "";
//                                    $interfaces[$key]['rx_speed'] = ($interfacestats['0']['rx-bits-per-second'] / 1024 / 1024) ?? $interfaces[$key]['rx-speed'] = "";
//                                }
//                            } else {
//                            }
//                        }
//                    }
//
//                    foreach ($interfaces as $interface) {
//                        if (array_key_exists('default-name', $interface)) {
//                            $interface['name'] = preg_replace("/\s/", "-", $interface['default-name']);
//                        } else {
//                            if (array_key_exists('name', $row)) {
//                                $interface['name'] = preg_replace("/\s/", "-", $interface['name']);
//                            }
//                        }
//                        $dinterface = DInterface::where('device_id', $device->id)->where('name', $interface['name'])->first();
//                        if (isset($dinterface)) {
//                            if (array_key_exists('tx_speed', $interface)) {
//                                $dinterface->txspeed = $interface['tx_speed'];
//                                if($dinterface->maxtxspeed < $interface['tx_speed']){
//                                    $dinterface->maxtxspeed = $interface['tx_speed'];
//                                }
//                                if($dinterface->maxrxspeed < $interface['rx_speed']){
//                                    $dinterface->maxrxspeed = $interface['rx_speed'];
//
//                                }
//                                $dinterface->rxspeed = $interface['rx_speed'];
//                                echo "$dinterface->name SPEED UPDATED \n";
//                                $dinterface->save();
//                            }
//                        }
//                    }
//                }
//            }catch(\Exception $e){
//
//            }
//        }
    }

    public function getMikrotikInterfaces($device){
        $interfaces = array();
        //echo "Getting interfaces for $device->ip \n";
        try{

            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/interface/print');
                    $READ       = $API->read();
                    foreach ($READ as $key => $row){
                        if ( ($row['type'] != "pppoe-in") and ($row['type'] != "l2tp-in")){
                            $interfaces[$key] = $row;
                            $name = $row['name'];
                            if($row['running']=="true"){
                                $API->write("/interface/monitor-traffic",false);
                                $API->write("=interface=".$name,false);
                                $API->write("=once=",true);
                                $interfacestats = $API->read();
                                if (array_key_exists('0',$interfacestats)){
                                    $interfaces[$key]['tx-speed'] = $interfacestats['0']['tx-bits-per-second'] ?? $interfaces[$key]['tx-speed'] = "";
                                    $interfaces[$key]['rx-speed'] = $interfacestats['0']['rx-bits-per-second'] ?? $interfaces[$key]['rx-speed'] = "";
                                }
                            }else{

                            }
                        }
                    }
                }else{
                    return $interfaces['0'] = "No-response";
                }
            }else{
                return  $interfaces['0'] = "No-response";
            }
            foreach ($interfaces as $interface){
                if (array_key_exists('default-name', $interface)) {
                    $interface['name'] = preg_replace("/\s/","-",$interface['default-name']);
                } else {
                    if(array_key_exists('name', $row)){
                        $interface['name'] = preg_replace("/\s/","-",$interface['name']);
                    }
                }
            }
            return $interfaces;

        }catch (\Exception $e){
            return;
        }
    }

    public function speedTest($device){
        try {
            if ($device->ping == "1") {
                echo "Starting $device->name \n";
                $API = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write("/tool/bandwidth-test",false);
                    $API->write("=direction=receive",false);
                    $API->write("=address=10.99.0.118",false);
                    $API->write("=duration=35s",false);
                    $API->write("=protocol=udp ",false);
                    $API->write("=user=admin ",false);
                    $API->write("=password=M0th3rF#cker ",true);
                    $READ = $API->read();
                }
            }
        }catch (\Exception $e){

        }

        $max = $READ["35"]["rx-current"]/1024/1024;
        \DB::table('interfaces')->where('device_id', $device->id)->update(['threshhold' => $max]);
    }

//    public function storeMikrotikDInterface($device){
//        try {
//            if ($device->ping == "1") {
//                echo "Starting $device->name \n";
//                $API = new RouterosAPI();
//                $API->debug = false;
//                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
//                    $API->write('/interface/print');
//                    $READ = $API->read();
//                    foreach ($READ as $key => $row) {
//                        if (($row['type'] != "pppoe-in") and ($row['type'] != "l2tp-in") and ($row['type']!="cap")) {
//                            if(array_key_exists('name', $row)){
//                                $name = $row['name'];
//                            }
//                            if (array_key_exists('.id', $row)) {
//                                $id = $row['.id'];
//                            } else {
//                                $id = "N/a";
//                            }
//
//                            if (array_key_exists('mac-address', $row)) {
//                                $mac_address = $row['mac-address'];
//                            } else {
//                                $mac_address = "N/a";
//                            }
//                            if (array_key_exists('type', $row)) {
//                                $type = $row['type'];
//                            } else {
//                                $type = "N/a";
//                            }
//                            if (array_key_exists('last-link-down-time', $row)) {
//                                $last_link_down_time = $row['last-link-down-time'];
//                            } else {
//                                $last_link_down_time = "N/a";
//                            }
//                            if (array_key_exists('last-link-up-time', $row)) {
//                                $last_link_up_time = $row['last-link-up-time'];
//                            } else {
//                                $last_link_up_time = "N/a";
//                            }
//                            if (array_key_exists('mtu', $row)) {
//                                $mtu = $row['mtu'];
//                            } else {
//                                $mtu = "N/a";
//                            }
//                            if (array_key_exists('actual-mtu', $row)) {
//                                $actual_mtu = $row['actual-mtu'];
//                            } else {
//                                $actual_mtu = "N/a";
//                            }
//                            if (array_key_exists('mtu', $row)) {
//                                $running = $row['running'];
//                            } else {
//                                $running = "N/a";
//                            }
//                            if (array_key_exists('mtu', $row)) {
//                                $disabled = $row['disabled'];
//                            } else {
//                                $disabled = "N/a";
//                            }
//
//                            $API->write('/interface/ethernet/monitor',false);
//                            $API->write("=numbers=$id", false);
//                            $API->write("=once=", true);
//                            $READ = $API->read();
//                            if (array_key_exists('0',$READ)){
//                                if (array_key_exists('rate', $READ[0])) {
//                                    $speed = $READ[0]['rate'];
//                                    if($speed=="10Gbps"){
//                                        $speed = "1000000000";
//                                    }
//                                    if($speed=="1Gbps"){
//                                        $speed = "1000000000";
//                                    }
//                                    if($speed=="100Mbps"){
//                                        $speed = "100000000";
//                                    }
//                                    if($speed=="10Mbps"){
//                                        $speed = "10000000";
//                                    }
//                                } else {
//                                    $speed = 0;
//                                }
//                            }else{
//                                $speed = 0;
//                            }
//                            $API->write('/interface/print',false);
//                            $API->write("=.proplist=name.oid", false);
//                            $API->write("?.id=$id", true);
//                            $READ = $API->read();
//                            if (array_key_exists('0',$READ)){
//                                if (array_key_exists('name.oid', $READ[0])) {
//                                    $default_name = preg_replace('/.1.3.6.1.2.1.2.2.1.2./','',$READ[0]['name.oid']);
//                                } else {
////                                    $default_name = "N/a";
//                                }
//                            }else{
////                                $speed = "N/a";
//                            }
//
//
//                            $device_id = $device->id;
//                            if (DInterface::where('default_name', '=', $default_name)->where('device_id',$device->id)->exists()) {
//                                $dinterface = DInterface::where('default_name', '=', $default_name)->where('device_id',$device->id)->first();
//                                $dinterface->name = $name;
//                                $dinterface->default_name = $default_name;
//                                $dinterface->mac_address = $mac_address;
//                                $dinterface->type = $type;
//                                $dinterface->previous_running_state = $dinterface->running;
//                                $dinterface->running = $running;
//                                $dinterface->previous_link_speed = $dinterface->link_speed;
//                                $dinterface->link_speed = $speed;
//                                if ($dinterface->link_speed != $dinterface->previous_link_speed){
//                                    $interfacelog = new Interfacelog();
//                                    $interfacelog->device_id = $device->id;
//                                    $interfacelog->dinterface_id = $dinterface->id;
//                                    $interfacelog->status = "$dinterface->name changed speed from ".($dinterface->previous_link_speed)." to ".($dinterface->link_speed)."";
//                                    $interfacelog->save();
//                                }
//                                if ($dinterface->running != $dinterface->previous_running_state){
//                                    $interfacelog = new Interfacelog();
//                                    $interfacelog->device_id = $device->id;
//                                    $interfacelog->dinterface_id = $dinterface->id;
//                                    $interfacelog->status = "$dinterface->name changed status from $dinterface->previous_running_state to $dinterface->running";
//                                    $interfacelog->save();
//                                }
//                                $dinterface->last_link_down_time = $last_link_down_time;
//                                $dinterface->last_link_up_time = $last_link_up_time;
//                                $dinterface->mtu = $mtu;
//                                $dinterface->actual_mtu = $actual_mtu;
//                                $dinterface->disabled = $disabled;
//                                $dinterface->device_id = $device_id;
//                                $dinterface->save();
//                            } else {
//                                $dinterface = new DInterface();
//                                $dinterface->name = $name;
//                                $dinterface->default_name = $default_name;
//                                $dinterface->mac_address = $mac_address;
//                                $dinterface->type = $type;
//                                $dinterface->last_link_down_time = $last_link_down_time;
//                                $dinterface->last_link_up_time = $last_link_up_time;
//                                $dinterface->mtu = $mtu;
//                                $dinterface->actual_mtu = $actual_mtu;
//                                $dinterface->running = $running;
//                                $dinterface->disabled = $disabled;
//                                $dinterface->device_id = $device_id;
//                                $dinterface->save();
//                            }
//                        }
//                    }
//
//                } else {
//                    echo "FAILED on $device->name \n";
//                    return $interfaces['0'] = "No-response";
//                }
//            } else {
//                echo "FAILED on $device->name \n";
//                return $interfaces['0'] = "No-response";
//            }
//
//            return "Success";
//        }catch(\Exception $e){
//            echo "FAILED on $device->name \n";
//        }
//
//    }


    public static function findPeakInterfaces(){

        $devices = Device::where('devicetype_id','1')->get();
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $finals[$device->id] = $themikrotiklibrary->getOneMikrotikinterfaces($device);
        }
        $themikrotiklibrary->graphMinMaxInterfaces($finals);
    }

    public function graphMinMaxinterfaces($results)
    {
        $time = time();
        foreach ($results as $deviceid => $result) {
            if ($result != "") {
                foreach ($result as $name => $interface) {
                    $maxrxtime = date( "Y-m-d h:m:i");
                    $maxrxtime = strtotime($maxrxtime);
                    $maxtxtime = date( "Y-m-d h:m:i");
                    $maxtxtime = strtotime($maxtxtime);
                    $data = array(
                        "host" => $deviceid,
                        "maxtx" => $interface['maxtx'],
                        "maxrx" => $interface['maxrx'],
                        "maxrxtime" => $maxrxtime,
                        "maxtxtime" => $maxtxtime,
                        "iname" => $name,
                    );
                    $value = 1;
                    //InfluxLibrary::writeToDB("dte","interfacesminmax",$data,$value);
                    echo $name . " has been insterted from " . $deviceid . "\n";
                }
            }else {

            }
        }
    }
    public function graphAllInterfaces($devices){
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $themikrotiklibrary->graphAllInterfacesbyDevice($device);
        }
    }

    public static function graphAllInterfacesNew($job){
        $devices = Device::where('devicetype_id','1')->orWhere('devicetype_id','7')->orWhere('devicetype_id','6')->get();
        if($devices->count() < 50){
            $count = 1;
        }else{
            $count = ($devices->count()/50);
        }

        if( ($count > 20)){
            $count = ($devices->count()/50);
            $chunks = $devices->chunk($count);
            foreach ($chunks[$job] as $device){
                $command = 'sleep 2s;/usr/bin/php /var/www/html/dte/artisan graphOneMikrotikInterfaces '.$device->id. '  > /dev/null &';
                exec($command);
            }
        }else{
            $count = ($devices->count()/50);
            $chunks = $devices->chunk($count);
            foreach ($chunks[$job] as $device){
                $command = 'sleep 2s;/usr/bin/php /var/www/html/dte/artisan graphOneMikrotikInterfaces '.$device->id. '  > /dev/null &';
                exec($command);
            }
        }
    }


    public static function getPPPOECountNew($job){
        $API = new RouterosAPI();
        try {

            $devices = Device::where('devicetype_id','1')->get();
            $count = ($devices->count()/20);
            $chunks = $devices->chunk($count);
            $themikrotiklibrary = new MikrotikLibrary();
            foreach ($chunks[$job] as $device){
                ///connect API
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $pppinfo = $themikrotiklibrary->getActivePPP($API, $device);
                    $device->active_pppoe = $pppinfo['active_pppoe'] ??  $device->active_pppoe = 0;
                    $device->maxactivepppoe = $pppinfo['maxactivepppoe'] ??  $device->maxactivepppoe = 0;
                    //active hotspot fetch
                    $hotspotinfo = $themikrotiklibrary->getActiveHotspot($API, $device);
                    $device->active_hotspot = $hotspotinfo['active_hotspot'] ??  $device->active_hotspot = 0;
                    $device->max_active_hotspot = $hotspotinfo['max_active_hotspot'] ??  $device->max_active_hotspot = 0;
                    $device->save();
                }
            }
        }catch(\Exception $e){

        }
    }



    public function graphAllInterfacesbyDevice($device){
        $themikrotiklibrary = new MikrotikLibrary();
        echo $device->id." \n";
        $exception = false;
        $connections_oid_root = "iso.3.6.1.2.1.31.1.1.1.6";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.31.1.1.1.6.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter64:/", $line);
                $line = trim($line[1]);
                $newarray[$newnewkey['0']]['bytes-in'] = $line;
            }
        } catch (\Exception $e) {
            $exception = true;
            $description = "No SNMP Response";
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if($fault->status == 0){
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }else{
                //else do nothing
            }
        }

        $connections_oid_root = "iso.3.6.1.2.1.31.1.1.1.10";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.31.1.1.1.10.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter64:/", $line);
                $line = trim($line[1]);
                $newarray[$newnewkey['0']]['bytes-out'] = $line;
            }
        } catch (\Exception $e) {

        }
        $connections_oid_root = "iso.3.6.1.2.1.2.2.1.2";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.2.2.1.2.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/STRING:/", $line);
                $line = trim($line[1]);
                $line = preg_replace('/"/','',$line);
                $newarray[$newnewkey['0']]['name'] = $line;
            }
        } catch (\Exception $e) {

        }

        $connections_oid_root = "iso.3.6.1.2.1.2.2.1.6";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.2.2.1.6.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Hex-STRING:/", $line);
                if(array_key_exists(1,$line)){
                    $line = trim($line[1]);
                    $line = preg_replace('/ /',':',$line);
                    $newarray[$newnewkey['0']]['mac'] = $line;
                }else{
                    $newarray[$newnewkey['0']]['mac'] = "";
                }
            }
        } catch (\Exception $e) {

        }
        $connections_oid_root = "iso.3.6.1.2.1.31.1.1.1.7";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.31.1.1.1.7.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter64:/", $line);
                if(array_key_exists(1,$line)){
                    $line = trim($line[1]);
                    $line = preg_replace('/ /',':',$line);
                    $newarray[$newnewkey['0']]['packets-in'] = $line;
                }else{
                    $newarray[$newnewkey['0']]['packets-in'] = "";
                }
            }
        } catch (\Exception $e) {

        }
        $connections_oid_root = "iso.3.6.1.2.1.31.1.1.1.11";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.31.1.1.1.11.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter64:/", $line);
                if(array_key_exists(1,$line)){
                    $line = trim($line[1]);
                    $line = preg_replace('/ /',':',$line);
                    $newarray[$newnewkey['0']]['packets-out'] = $line;
                }else{
                    $newarray[$newnewkey['0']]['packets-out'] = "";
                }
            }
        } catch (\Exception $e) {

        }
        $connections_oid_root = "iso.3.6.1.2.1.2.2.1.14";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.2.2.1.14.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter32:/", $line);
                if(array_key_exists(1,$line)){
                    $line = trim($line[1]);
                    $line = preg_replace('/ /',':',$line);
                    $newarray[$newnewkey['0']]['errors-in'] = $line;
                }else{
                    $newarray[$newnewkey['0']]['errors-in'] = "";
                }
            }
        } catch (\Exception $e) {

        }
        $connections_oid_root = "iso.3.6.1.2.1.2.2.1.20";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.2.2.1.20.";
            $result = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $line = preg_split("/Counter32:/", $line);
                if(array_key_exists(1,$line)){
                    $line = trim($line[1]);
                    $line = preg_replace('/ /',':',$line);
                    $newarray[$newnewkey['0']]['errors-out'] = $line;
                }else{
                    $newarray[$newnewkey['0']]['errors-out'] = "";
                }
            }
        } catch (\Exception $e) {

        }
        try{
            if(isset($newarray)){
                foreach($newarray as $key => $row){
                    if(array_key_exists('mac',$row)){
                        if (DInterface::where('default_name', '=', $key)->where('device_id',$device->id)->exists()) {
                            $interface = DInterface::where('default_name', '=', $key)->where('device_id',$device->id)->first();
                            if(
                                ($interface->type!="pptp-in")
                                AND ($interface->type!="eoip")
                                AND ($interface->type!="sstp-in")
                                AND ($interface->type!="cap")
                                AND ($interface->type!="pppoe-out")
                                AND ($interface->type!="pptp-out")
                            ){
                                $data = array(
                                    "host" => $interface->id,
                                    "txvalue" => $row['bytes-out'],
                                    "rxvalue" => $row['bytes-in'],
                                    "ifInErrors" => $row['errors-in'],
                                    "ifOutErrors" => $row['errors-out'],
                                    "ifInPackets" => $row['packets-in'],
                                    "ifOutPackets" => $row['packets-out'],
                                    "iname" => preg_replace('/ /','_',$row['name'])
                                );
                                $value = 1;
                                //InfluxLibrary::writeToDB("dte", "interfaces", $data, $value);
                                $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($device->id) . "/" . trim($interface->id) . ".rrd";
                                if (!file_exists($rrdFile)) {
                                    echo "NO RRD FOUND \n";
                                    $options = array(
                                        '--step',config('rrd.step'),
                                        "--start", "-1 day",
                                        "DS:rxvalue:GAUGE:900:U:U",
                                        "DS:txvalue:GAUGE:900:U:U",
                                        "DS:ifInErrors:GAUGE:900:U:U",
                                        "DS:ifOutErrors:GAUGE:900:U:U",
                                        "DS:ifInPackets:GAUGE:900:U:U",
                                        "DS:ifOutPackets:GAUGE:900:U:U",
                                        "DS:Availabilty:GAUGE:900:U:U",
                                        "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
                                    );
                                    echo "CREATING RRD " . trim($device->id) . "/" . trim($row['name']) . ".rrd \n";
                                    $command = "mkdir /var/www/html/dte/rrd/interfaces/$device->id";
                                    exec($command);
                                    $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($device->id) . "/" . trim($interface->id) . ".rrd";
                                    if (!\rrd_create($rrdFile, $options)) {
                                        echo rrd_error();
                                    }
                                } else {
                                    $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($device->id) . "/" . trim($interface->id) . ".rrd";
                                    if ($interface->running == "true") {
                                        $running = 100;
                                    } else {
                                        $running = 0;
                                    }
                                    $time = time();
                                    //\Log::info("Updating RRD for $rrdFile at ".time());
                                    $updator = new \RRDUpdater($rrdFile);
                                    $updator->update(array(
                                        "txvalue" => $data["txvalue"],
                                        "rxvalue" => $data["rxvalue"],
                                        "ifInErrors" => $data["ifInErrors"],
                                        "ifOutErrors" => $data["ifOutErrors"],
                                        "ifInPackets" => $data["ifInPackets"],
                                        "ifOutPackets" => $data["ifOutPackets"],
                                        "Availabilty" => $running
                                    ), $time);
                                }
                            }
                            echo $data['iname'] ."--".$data['txvalue']." / ".$data['rxvalue']. " has been insterted from " . $device->name . "\n";
                        }else{
                            echo($row['mac']." - ".$row['name']. " does not exist! \n");

                        }

                    }
                }
            }
        }catch (\Exception $e){
            $e->getMessage();
        }
        if($exception == false){
            $description = "No SNMP Response";
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
            }
        }
    }


    public function getAllMikrotikRoutes($devices){
        foreach ($devices as $device){
            $device->getMikrotikDefaultGateway();
            $device->default_gateway_id = $device->getIdFromIp($device->default_gateway);
            $device->save();
        }
    }

    public static function hacker($job){
        $results = array();
        $pppoeclients = Pppoeclient::where("type","!=","hotspot")->get();
        $count = ($pppoeclients->count()/400);
        $chunks = $pppoeclients->chunk($count);
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($chunks[$job] as $device){
            if($themikrotiklibrary->fix_hacked_router($device->ip)){
                $results[$device->ip] = "Success";
            }else{
                $results[$device->ip] = "Failed";
            }
        }
    }

    public function fix_services_igen($device){
        if ($device->ping == "1") {
            $API = new RouterosAPI();
            $API->debug = true;
            try {
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/snmp/set', false);
                    $API->write('=enabled=yes', false);
                    $API->write('=trap-version=2', false);
                    $API->write('=trap-community=public', true);
                    $READ = $API->read();

                }
            }catch(\Exception $e){

            }
        }
    }

    public function testClientSpeed($pppoe)
    {
        echo "Starting $pppoe->ip \n";
        $API = new RouterosAPI();
        $API->debug = true;
        if ($API->connect($pppoe->ip, "admin", "!Cpe@BbW!1")) {
            $API->write('/ip/route/print', false);
            $API->write('?connect=yes', true);
            $READ = $API->read();
            foreach ($READ as $row) {
                if (strpos($row['dst-address'], '/32')) {
                    $gateway = (preg_replace('/\/32/', '', $row['dst-address']));
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=transmit', true);
                    $READ = $API->read();
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=receive', true);
                    $READ = $API->read();
                }
            }
        }elseif ($API->connect($pppoe->ip, "admin", "laroch007")) {
            $API->write('/ip/route/print', false);
            $API->write('?connect=yes', true);
            $READ = $API->read();
            foreach ($READ as $row) {
                if (strpos($row['dst-address'], '/32')) {
                    $gateway = (preg_replace('/\/32/', '', $row['dst-address']));
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=transmit', true);
                    $READ = $API->read();
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=receive', true);
                    $READ = $API->read();
                }
            }
        }elseif ($API->connect($pppoe->ip, "admin", "laroch007")) {
            $API->write('/ip/route/print', false);
            $API->write('?connect=yes', true);
            $READ = $API->read();
            foreach ($READ as $row) {
                if (strpos($row['dst-address'], '/32')) {
                    $gateway = (preg_replace('/\/32/', '', $row['dst-address']));
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=transmit', true);
                    $READ = $API->read();
                    $API->write('/tool/bandwidth-test', false);
                    $API->write('=address=' . $gateway, false);
                    $API->write('=duration=30s', false);
                    $API->write('=protocol=tcp', false);
                    $API->write('=user=admin', false);
                    $API->write('=password=!BW24?Huk1', false);
                    $API->write('=direction=receive', true);
                    $READ = $API->read();
                }
            }
        }
    }


    public function fix_hacked_router($ip){
        $vpns = ['vpn','aa','bb','cc','dd','ee','ff','gg','hh','ii','jj','kk','ll','mm','nn','oo','pp','qq','rr','ss','tt','uu','vv','ww','xx','yy','zz'];
        $API = new RouterosAPI();
        $API->debug = true;
        $passwords = [
            "!Cpe@BbW!1",
            "clp@BbW!",
            "L@roch00&",
            "clp@BbW!1",
            "laroch007",
            "admin",
            ""
        ];
        $connect = false;
        foreach($passwords as $password){
            if($connect==false){
            echo "Trying $password \n";
            try{
                if ($API->connect($ip, "admin", $password)) {
                    echo "Connected \n";
                    $connect = true;
                    $API->write('/system/script/print', true);
                    $READ = $API->read();
                    foreach ($READ as $line) {
                        $API->write('/system/script/remove', false);
                        $API->write("=numbers=" . $line['.id'], true);
                        $READ = $API->read();
                    }

                    $API->write('/system/scheduler/print', true);
                    $READ = $API->read();
                    foreach ($READ as $line) {
                        $API->write('/system/scheduler/remove', false);
                        $API->write("=numbers=" . $line['.id'], true);
                        $READ = $API->read();
                    }

                    $API->write('/ip/proxy/set', false);
                    $API->write('=enabled=no', true);
                    $READ = $API->read();

                    $API->write('/ip/socks/set', false);
                    $API->write('=enabled=no', true);
                    $READ = $API->read();

                    $API->write('/ip/socks/access/print', true);
                    $READ = $API->read();
                    foreach ($READ as $line) {
                        $API->write('/ip/proxy/access/remove', false);
                        $API->write("=numbers=" . $line['.id'], true);
                        $READ = $API->read();
                    }


                    $API->write('/file/print');
                    $READ = $API->read();
                    foreach ($READ as $file) {
                        if (preg_match("/pdate.ashx/", $file['name'])) {
                            $API->write('/file/remove', false);
                            $API->write("=numbers=" . $file['.id'], true);
                            $READ = $API->read();
                        }
                    }

                    foreach ($vpns as $vpn) {
                        $API->write('/ppp/secret/remove', false);
                        $API->write('=numbers=' . $vpn, true);
                        $READ = $API->read();
                    }


//                    $API->write('/ip/firewall/nat/print', true);
//                    $READ = $API->read();
//                    foreach ($READ as $read) {
//                        if (
//                            (array_key_exists('src-address', $read)) or
//                            (array_key_exists('dst-address', $read)) or
//                            (array_key_exists('out-interface', $read))) {
//
//                        } else {
//                            if ($read['action'] == "masquerade") {
//                                $API->write('/ip/firewall/nat/disable', false);
//                                $API->write("=numbers=" . $read['.id'], true);
//                                $NEW = $API->read();
//                            }
//                        }
//                    }

                    $API->write('/ip/address/print', true);
                    $READ = $API->read();
                    foreach ($READ as $line) {
                        if ((array_key_exists('address', $line))) {
                            $addresses[] = $line['address'];
                        }
                    }

                    $API->write('/interface/pppoe-client/print', true);
                    $READ = $API->read();
                    foreach ($READ as $read) {
//                        foreach ($addresses as $address) {
//                            $API->write('/ip/firewall/nat/print', false);
//                            $API->write("?chain=srcnat", false);
//                            $API->write("?action=masquerade", false);
//                            $API->write("?src-address=" . $address, false);
//                            $API->write("?out-interface=" . $read['name'], true);
//                            $done = $API->read();
//                            foreach ($done as $notdone) {
//                                $API->write('/ip/firewall/nat/remove', false);
//                                $API->write('=numbers=' . $notdone['.id'], true);
//                                $complete = $API->read();
//
//                            }
//                            $API->write('/ip/firewall/nat/add', false);
//                            $API->write("=chain=srcnat", false);
//                            $API->write("=action=masquerade", false);
//                            $API->write("=src-address=" . $address, false);
//                            $API->write("=out-interface=" . $read['name'], true);
//                            $done = $API->read();
//                        }
                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=tcp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=tcp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();

                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=udp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=udp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();
                    }
                    $API->write('/interface/pptp-client/print', true);
                    $READ = $API->read();
                    foreach ($READ as $read) {
                        foreach ($addresses as $address) {
                            $API->write('/ip/firewall/nat/print', false);
                            $API->write("?chain=srcnat", false);
                            $API->write("?action=masquerade", false);
                            $API->write("?src-address=" . $address, false);
                            $API->write("?out-interface=" . $read['name'], true);
                            $done = $API->read();
                            foreach ($done as $notdone) {
                                $API->write('/ip/firewall/nat/remove', false);
                                $API->write('=numbers=' . $notdone['.id'], true);
                                $complete = $API->read();

                            }
                            $API->write('/ip/firewall/nat/add', false);
                            $API->write("=chain=srcnat", false);
                            $API->write("=action=masquerade", false);
                            $API->write("=src-address=" . $address, false);
                            $API->write("=out-interface=" . $read['name'], true);
                            $done = $API->read();
                        }

                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=tcp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=tcp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();

                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=udp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=udp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();
                    }

                    $API->write('/interface/l2tp-client/print', true);
                    $READ = $API->read();
                    foreach ($READ as $read) {
//                        foreach ($addresses as $address) {
//                            $API->write('/ip/firewall/nat/print', false);
//                            $API->write("?chain=srcnat", false);
//                            $API->write("?action=masquerade", false);
//                            $API->write("?src-address=" . $address, false);
//                            $API->write("?out-interface=" . $read['name'], true);
//                            $done = $API->read();
//                            foreach ($done as $notdone) {
//                                $API->write('/ip/firewall/nat/remove', false);
//                                $API->write('=numbers=' . $notdone['.id'], true);
//                                $complete = $API->read();
//
//                            }
//                            $API->write('/ip/firewall/nat/add', false);
//                            $API->write("=chain=srcnat", false);
//                            $API->write("=action=masquerade", false);
//                            $API->write("=src-address=" . $address, false);
//                            $API->write("=out-interface=" . $read['name'], true);
//                            $done = $API->read();
//                        }

                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=tcp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=tcp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();

                        $API->write('/ip/firewall/filter/print', false);
                        $API->write("?chain=input", false);
                        $API->write("?action=drop", false);
                        $API->write("?dst-port=53", false);
                        $API->write("?protocol=udp", false);
                        $API->write("?in-interface=" . $read['name'], true);
                        $done = $API->read();
                        foreach ($done as $notdone) {
                            $API->write('/ip/firewall/filter/remove', false);
                            $API->write('=numbers=' . $notdone['.id'], true);
                            $complete = $API->read();
                        }

                        $API->write('/ip/firewall/filter/add', false);
                        $API->write("=chain=input", false);
                        $API->write("=action=drop", false);
                        $API->write("=dst-port=53", false);
                        $API->write("=protocol=udp", false);
                        $API->write("=in-interface=" . $read['name'], true);
                        $done = $API->read();
                    }


                    $API->write('/user/set', false);
                    $API->write('=password=!Cpe@BbW!1', false);
                    $API->write('=numbers=admin', true);
                    $READ = $API->read();

                    $API->write('/ip/service/set', false);
                    $API->write('=numbers=ftp', false);
                    $API->write('=address=10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,169.159.128.0/19,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22', true);
                    $READ = $API->read();

                    $API->write('/ip/service/set', false);
                    $API->write('=numbers=winbox', false);
                    $API->write('=address=10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,169.159.128.0/19,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22', true);
                    $READ = $API->read();

                    $API->write('/ip/service/set', false);
                    $API->write('=numbers=www', false);
                    $API->write('=address=10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,169.159.128.0/19,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22', true);
                    $READ = $API->read();

                    $API->write('/ip/service/set', false);
                    $API->write('=numbers=ssh', false);
                    $API->write('=address=10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,169.159.128.0/19,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22', true);
                    $READ = $API->read();

                    $API->write('/ip/service/set', false);
                    $API->write('=numbers=telnet', false);
                    $API->write('=address=10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,169.159.128.0/19,154.119.56.0/21,213.150.200.0/21,41.223.24.0/22', true);
                    $READ = $API->read();
                    if ($connect != false) {
                        return $connect;
                    }
                    }else{
                    }
                }catch(\Exception $e){
                }
            }
        }

        if($connect == false){
            //echo $ip."\n";
        }
        return $connect;
    }

    public function backupMikrotik($device){
        try{
            echo "Backup device $device->name </br>";
            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    echo "Logged into device $device->name </br>";

                    $API->write('/export', false);
                    $API->write('=file=dte_backup.rsc', true);
                    sleep(10);
                    echo "Downloading file </br>";

                    $filename = $device->getbackupfile($device);
                }
            } else {
                return "No backup made";
            }
            sleep(1);

            if (isset($filename)){
                exec(" sed -i -e 's/=true/=yes/g ' ".config('mikrotik.backup_storage') ."$filename".".rsc");
                exec(" sed -i -e 's/=false/=no/g ' ".config('mikrotik.backup_storage') ."$filename".".rsc");
                echo "downloaded $filename </br>";
                return $filename;
            }else{
                return "No backup made";
            }
        }catch (\Exception $e){
            echo $e;
            return;
        }
    }

    public function getSerialNumber($device){
        try{
            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/system/routerboard/print', true);
                    $READ = $API->read();
                    if (array_key_exists('0',$READ)){
                        if (array_key_exists('serial-number',$READ['0'])){
                            $description = "Serial nr changed from $device->previous_serial_nr to $device->serial_no";
                            if ($device->previous_serial_nr != $device->serial_no) {
                                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                                if(isset($fault->status)) {
                                    if($fault->status == 0){
                                        //create new fault
                                        $new_fault = new Fault();
                                        $new_fault->description = $description;
                                        $new_fault->device_id = $device->id;
                                        $new_fault->status = 1;
                                        $new_fault->save();
                                    }else{
                                        //else do nothing
                                    }
                                }else{
                                    $new_fault = new Fault();
                                    $new_fault->description = $description;
                                    $new_fault->device_id = $device->id;
                                    $new_fault->status = 1;
                                    $new_fault->save();
                                }
                            } else {
                                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                                if(isset($fault)){
                                    $fault->status = 0;
                                    $fault->save();
                                    //set fault to resolved
                                }
                                //set fault to resolved
                            }
                            $device->previous_serial_nr = $device->serial_no;
                            $device->serial_no = $READ['0']['serial-number'];
                            $device->save();
                            echo "$device->name serial number updated to $device->serial_no"."\n";
                        }
                    }

                }
            }
        }catch (\Exception $e){
            return;
        }
    }


    public function checkMikrotikBackup($device){
        if (file_exists("/var/www/html/dte/storage/mikrotikbackups/$device->ip.rsc")) {
            $device->backed_up = "1";
            exec(" ls -l --time-style=+%Y%m%d /var/www/html/dte/storage/mikrotikbackups/$device->ip.rsc", $results);

            foreach ($results as $result) {
                $fresult[] = explode(' ', $result);
                if (!empty($fresult['0']['5'])) {
                    $device->date_backed_up = $fresult['0']['5'];
                    $device->backed_up      = "1";
                    $device->save();
                }
            }
        } else {
            $device->backed_up      = "0";
            $device->date_backed_up = "n/a";
            return $device->name." not backup up";
        }
        $device->save();
    }

    public function rebootMikrotik($device){
        try{
            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($this->ip, $device->md5_username, $device->md5_password)) {
                    $API->comm('/system/reboot');
                    $API->disconnect();
                }
            }
        } catch (\Exception $e){
            return;
        }
    }

    public function updateMikrotikSoftware($device){
        try {
            if ($device->ping == "1") {
                $API        = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($this->ip, $device->md5_username, $device->md5_password)) {
                    $API->comm('/system/package/update/check-for-updates');
                    $API->comm('/system/package/update/download');
                    $API->write('/system/package/enable', false);
                    $API->write('=ipv6=');
                    $API->disconnect();
                }
            }
        }catch (\Exception $e){
            return;
        }
    }

    public function getMikrotikDefaultGateway($device){
        try{
            if ($device->ping == "1") {
                $API = new RouterosAPI();
                $API->debug = false;
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    $API->write('/ip/route/print', false);
                    $API->write('?-routing-mark', false);
                    $API->write('?active=true', false);
                    $API->write('?dst-address=0.0.0.0/0', true);
                    $READ = $API->read();
                    foreach ($READ as $row){
                        if (array_key_exists('dst-address',$row)){
                            if (($row['dst-address'] == "0.0.0.0/0") AND ($row['active']=="true")){
                                if ($device->getIdFromIp($device->default_gateway)=="0"){
                                    $device->default_gateway = $row['gateway'] ?? $device->default_gateway = "Unknown";
                                    echo "default gateway NOT found ".$device->name." $device->default_gateway\n";
                                }else{
                                    $device->default_gateway = $row['gateway'] ?? $device->default_gateway = "Unknown";
                                    $device->default_gateway_id = $device->getIdFromIp($row['gateway']);
                                    $device->save();
                                }
                            }
                        }
                    }
                }
            }
        }catch (\Exception $e){
            return;
        }
    }

    public function getMikrotikActivePPPOES($API,$device){
        $API->write('/ppp/active/print');
        $READ = $API->read();
        foreach ($READ as $line){
            if (array_key_exists('name',$line)){
                if (Pppoeclient::where('username', '=', $line['name'])->exists()) {
                    $datetime = date_create()->format('Y-m-d H:i:s');
                    $pppoe = Pppoeclient::where('username',$line['name'])->first();
                    $pppoe->username = $line['name'];
                    $pppoe->mac = $line['caller-id'];
                    $pppoe->uptime = $line['uptime'];
                    $pppoe->ip = $line['address'];
                    $pppoe->device_id = $device->id;
                    $pppoe->is_online = 1;
                    $pppoe->type = "pppoe";
                    $pppoe->last_seen = $datetime;
                    $pppoe->is_notified = 0;
                    $pppoe->save();
                }else{
                    $pppoe = new Pppoeclient();
                    $pppoe->username = $line['name'];
                    $pppoe->mac = $line['caller-id'];
                    $pppoe->uptime = $line['uptime'];
                    $pppoe->ip = $line['address'];
                    $pppoe->type = "pppoe";
                    $pppoe->device_id = $device->id;
                    $pppoe->is_online = 1;
                    $pppoe->last_seen = new \DateTime();
                    $pppoe->is_notified = 0;
                    echo $pppoe->username." created \n";
                    $pppoe->save();
                }
            }
        }
    }

    public function getHotspotClients($API,$device){
        $API->write('/ip/hotspot/active/print');
        $READ = $API->read();

        foreach ($READ as $line){
            if (array_key_exists('user',$line)){
                if (Pppoeclient::where('username', '=', $line['user'])->exists()) {
                    $datetime = date_create()->format('Y-m-d H:i:s');
                    $pppoe = Pppoeclient::where('username',$line['user'])->first();
                    $pppoe->username = $line['user'];
                    $pppoe->mac = $line['mac-address'];
                    $pppoe->uptime = $line['uptime'];
                    $pppoe->ip = $line['address'];
                    $pppoe->device_id = $device->id;
                    $pppoe->is_online = 1;
                    $pppoe->type = "hotspot";
                    $pppoe->last_seen = $datetime;
                    $pppoe->is_notified = 0;
                    $pppoe->save();
                }else{
                    $pppoe = new Pppoeclient();
                    $pppoe->username = $line['user'];
                    $pppoe->mac = $line['mac-address'];
                    $pppoe->uptime = $line['uptime'];
                    $pppoe->ip = $line['address'];
                    $pppoe->device_id = $device->id;
                    $pppoe->is_online = 1;
                    $pppoe->type = "hotspot";
                    $pppoe->last_seen = new \DateTime();
                    $pppoe->is_notified = 0;
                    echo $pppoe->username." created \n";
                    $pppoe->save();
                }
            }
        }
    }



    public function getBGPINfo($API, $device)
    {
        echo "getBGPINfo \n";
        $API->write('/routing/bgp/peer/print');

        $READ = $API->read();
        foreach ($READ as $row) {
            if (array_key_exists('name', $row)) {
                $name = $row['name'];
                $remote_ip = $row['remote-address'];
                $remote_as = $row['remote-as'];
                $default_originate = $row['default-originate'];
                $state = $row['state'] ?? $state = "unknown";
                $prefix_count = $row['prefix-count'] ?? $prefix_count = 0;
                $disabled = $row['disabled'];
                $uptime = $row['uptime'] ?? $uptime = 0;

                if (BGPPeer::where('remote_address', '=', $remote_ip)->exists()) {
                    $bgppeer = BGPPeer::where('remote_address', '=', $remote_ip)->first();
                    $bgppeer->remote_as = $remote_as;
                    $bgppeer->remote_address = $remote_ip;
                    $bgppeer->device_id = $device->id;
                    $bgppeer->name = $name;
                    $bgppeer->default_originate = $default_originate;
                    $bgppeer->state = $state;
                    if ($bgppeer->state == "established") {
                        $bgppeer->acknowledged = "0";
                        $acknowledgement = Acknowledgement::where('bgppeer_id', $bgppeer->id)->where('active', "1")->first();
                        if (count($acknowledgement)) {
                            $acknowledgement->active = "0";
                            $acknowledgement->save();
                        }
                    }
                    $bgppeer->prefix_count = $prefix_count;
                    $bgppeer->disabled = $disabled;
                    if ($bgppeer->disabled == "true") {
                        $bgppeer->acknowledged = "0";
                        $acknowledgement = Acknowledgement::where('bgppeer_id', $bgppeer->id)->where('active', "1")->first();
                        if (count($acknowledgement)) {
                            $acknowledgement->active = "0";
                            $acknowledgement->save();
                        }
                    }
                    $bgppeer->uptime = $uptime;
                    $bgppeer->save();

                } else {
                    $bgppeer = new BGPPeer;
                    $bgppeer->remote_as = $remote_as;
                    $bgppeer->remote_address = $remote_ip;
                    $bgppeer->device_id = $device->id;
                    $bgppeer->default_originate = $default_originate;
                    $bgppeer->name = $name;
                    $bgppeer->state = $state;
                    $bgppeer->prefix_count = $prefix_count;
                    $bgppeer->disabled = $disabled;
                    $bgppeer->uptime = $uptime;
                    $bgppeer->save();
                }
            }
        }

        $API->write('/routing/bgp/instance/print');
        $READ = $API->read();

        foreach ($READ as $row) {
            if ($row['name'] == "default")
                $device->as_number = $row['as'];
            $device->save();
        }
    }

    public function getBlockedGhosts($devices)
    {
        foreach ($devices as $device) {
            try {
                if ($device->ping == "1") {
                    $API = new RouterosAPI();
                    $API->debug = false;
                    ///connect API
                    if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                        $API->write('/ip/firewall/address-list/print');
                        $READ = $API->read();
                        foreach ($READ as $row) {
                            if ($row['list'] == "NON PPPOE") {
                                if (!Ghost::where('ip', '=', $row['address'])->exists()) {
                                    $ghost = new Ghost();
                                    $ghost->ip = $row['address'];
                                    $ghost->device_id = $device->id;
                                    $ghost->save();
                                }
                            }
                        }
                    }
                }
            }catch (\Exception $e){
                return;
            }
        }
    }

    public function getHighestMikrotikInterfaces(){
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $query = "SELECT * FROM interfaces WHERE time > now() - 15m  ORDER BY time DESC;";
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
                $array[] = array(
                    "host" => $stat->host,
                    "iname" => $stat->iname,
                    "time" => $stat->time,
                    "rxvalue" => $stat->rxvalue,
                    "txvalue" => $stat->txvalue
                );
            }
        }

        return $array;
    }

    public function getAllIPs($themikrotiklibrary){
        $devices = Device::where('devicetype_id','1')->get();
        foreach ($devices as $device){
            $API = new RouterosAPI();
            $API->debug = false;
            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                $themikrotiklibrary->getIPs($API, $device);
            }
        }

    }

    public static function getMikotikVoltages(){
        $devices = Device::where('voltage_monitor','1')->get();
        $themikrotiklibrary = new MikrotikLibrary();
        foreach ($devices as $device){
            $themikrotiklibrary->getVolts($device);
        }
    }

    public function getVolts($device){
        echo $device->name."  ".$device->id." \n";
        $API = new RouterosAPI();
        try {
            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                $API->write('/system/health/print');
                $READ = $API->read();
                if (array_key_exists('0', $READ)) {
                    if ($READ['0'] != null) {
                        if (array_key_exists('voltage', $READ[0])) {
                            $device->volts = $READ[0]['voltage'];
                            echo $device->volts."\n";
                            $device->voltage_seen_at = new \DateTime();
                            $device->save();
                        }
                    }
                }
            }
        }catch(\Exception $e){
            echo $e."\n";
        }
    }

    public function getAllIPNeighbors($device)
    {
        echo "Getting Ip neighbors \n";
        $API = new RouterosAPI();
        $API->debug = false;
        $themikrotiklibrary = new MikrotikLibrary();
        ///connect API
        ///
        echo "Ping is live starting API calls $device->name \n";
        if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
            $themikrotiklibrary->getIPNeighbours($API, $device);
        }
    }

    public function updateMikrotik($device)
    {
        try {
            echo "updateMikrotik \n";
            if ($device->ping == "1") {
                echo "Ping is live starting API calls \n" ;
                $API = new RouterosAPI();
                $API->debug = false;
                echo "Connecting API... \n";
                if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                    echo "Connected API successfully!!! \n";
                    //instantiate mikrotik api library
                    $themikrotiklibrary = new Mikrotiklibrary();
                    echo "Standard calls \n";
                    $device->name = $themikrotiklibrary->getSystemIdentity($API, $device);
                    $device->dns_server = $themikrotiklibrary->getDnsServer($API, $device);
                    $device->pptp_server = $themikrotiklibrary->getPPTPServer($API, $device);
                    $device->sstp_server = $themikrotiklibrary->getSSTPServer($API, $device);
                    $device->l2tp_server = $themikrotiklibrary->getL2TPServer($API, $device);
                    $device->ovpn_server = $themikrotiklibrary->getOVPNServer($API, $device);
                    $themikrotiklibrary->getSerialNumber($device);
                    echo "System resources fetch \n";
                    //
                    $systemresources = $themikrotiklibrary->getSystemResources($API, $device);
                    $device->cpu = $systemresources['cpu'];
                    $device->total_memory = $systemresources['total_memory'];
                    $device->free_memory = $systemresources['free_memory'];
                    $device->model = $systemresources['model'];
                    $device->soft = $systemresources['soft'];
                    try{
                        $uptime       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.2.1.1.3");
                        $uptime       = preg_split("/Timeticks: /", $uptime['.1.3.6.1.2.1.1.3.0']);
                        $uptime       = preg_split("/\)/", $uptime['1']);
                        $uptime = preg_replace('/\(/','',$uptime['0']) ?? $device->uptime = "N/A";
                        $uptime = round($uptime/100,0);
                    }catch (\Exception $e){
                        echo $e."\n";
                    }
                    $device->uptime = $uptime;

                    $device->used_memory = round($systemresources['used_memory']/$device->total_memory*100,2);
                    //ip addresses get
                    // $themikrotiklibrary->getMikrotikDefaultGateway($device);
                    echo "Getting active PPP sessions \n";
                    $themikrotiklibrary->getPPPOEClients($device);
                    $pppinfo = $themikrotiklibrary->getActivePPP($API, $device);
                    $device->active_pppoe = $pppinfo['active_pppoe'] ??  $device->active_pppoe = 0;
                    $device->maxactivepppoe = $pppinfo['maxactivepppoe'] ??  $device->maxactivepppoe = 0;
                    echo "Getting active Hotspot sessions \n";
                    $themikrotiklibrary->getHotspotClients($API, $device);
                    $hotspotinfo = $themikrotiklibrary->getActiveHotspot($API,$device);
                    $device->active_hotspot = $hotspotinfo['active_hotspot'] ??  $device->active_hotspot = 0;
                    $device->max_active_hotspot = $hotspotinfo['max_active_hotspot'] ??  $device->max_active_hotspot = 0;
                    $themikrotiklibrary->getPorts($device);
                    //$themikrotiklibrary->storeMikrotikDInterface($device);
                    //system health checks
                    $systemhealth = $themikrotiklibrary->getSystemHealth($API, $device);
                    if (array_key_exists('volts', $systemhealth)) {
                        $device->volts = $systemhealth['volts'];
                    } else {
                        $device->volts = "n/a";
                    }
                    if (array_key_exists('current', $systemhealth)) {
                        $device->current = $systemhealth['current'];
                    } else {
                        $device->current = "n/a";
                    }
                    if (array_key_exists('temperature', $systemhealth)) {
                        $device->temp = $systemhealth['temperature'];
                    } else {
                        $device->temp = "n/a";
                    }
                    if (array_key_exists('psu1state', $systemhealth)) {
                        $device->psu1 = $systemhealth['psu1state'];
                    } else {
                        $device->psu1 = 1;
                    }
                    if (array_key_exists('psu2state', $systemhealth)) {
                        $device->psu2 = $systemhealth['psu2state'];
                    } else {
                        $device->psu2 = 1;
                    }

                    //system routerboard info
                    $device->firm = $themikrotiklibrary->getSystemRouterboard($API, $device);


                    //active ppp fetch

                    //bgp fetch
                    try{
                        $themikrotiklibrary->getBGPINfo($API, $device);
                    }catch(\Exception $e){

                    }
//                    $device->pollstatus = 1;
                    $device->lastsnmpupdate = new \DateTime();
                    $device->save();

                    $data = array(
                        "host" => $device->id,
                        "cpu" => $device->cpu,
                        "freem" => $device->used_memory,
                        "temp" => $device->temp,
                        "volts" => $device->volts,
                        "pppoe" =>$device->active_pppoe
                    );

                    $rrdFile = "/var/www/html/dte/rrd/mikrotiks/".trim($device->id).".rrd";
                    if (!file_exists($rrdFile)) {
                        echo "NO RRD FOUND \n";
                        $options = array(
                            '--step',config('rrd.step'),
                            "--start", "-1 day",
                            "DS:cpu:GAUGE:900:U:U",
                            "DS:freem:GAUGE:900:U:U",
                            "DS:temp:GAUGE:900:U:U",
                            "DS:volts:GAUGE:900:U:U",
                            "DS:pppoe:GAUGE:900:U:U",
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
                            "cpu" => $data["cpu"],
                            "freem" => $data["freem"],
                            "volts" => $data["volts"],
                            "pppoe" => $data["pppoe"],
                            "temp" => $data["temp"],
                        ), $time);
                    }
                } else {
//                    $device->pollstatus = 0;
                    //\Log::info("$device->id $device->name  Failed");
                    echo "Device Failed! \n";
                }
            }
            else {
//                    $device->pollstatus = 0;
                //\Log::info("$device->id $device->name  Failed");
                echo "Device Failed! \n";
            }
        } catch (\Exception $e) {
            echo "Could not log in via API !!!! ¸\n ";
            echo "$e \n";
//            $device->pollstatus = 0;
        }
        $device->save();
    }


    public function getPorts($device)
    {
        $API = new RouterosAPI();
        $API->debug = false;
        ///connect API
        ///
        try {
            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                $API->write('/ip/service/print');
                $READ       = $API->read();
                foreach($READ as $row){
                    if(array_key_exists('name',$row)){
                        if($row['name']=="ftp"){
                            $device->ftp_port = $row['port'];
                        }else{
                        }
                        if($row['name']=="winbox"){
                            $device->winbox_port = $row['port'];
                        }else{
                        }
                        if($row['name']=="ssh"){
                            $device->ssh_port = $row['port'];
                        }else{
                        }
                        if($row['name']=="telnet"){
                            $device->telnet_port = $row['port'];
                        }else{
                        }
                        if($row['name']=="www"){
                            $device->http_port = $row['port'];
                        }else{
                        }
                    }
                }
            }
        }catch (\Exception $e){

        }
        return "21";
    }

    public function getPPPOEClients($device)
    {
        $API = new RouterosAPI();
        $API->debug = false;
        ///connect API
        ///
        try {
            if ($API->connect($device->ip, $device->md5_username, $device->md5_password)) {
                $themikrotiklibrary = new Mikrotiklibrary();
                $themikrotiklibrary->getMikrotikActivePPPOES($API,$device);
            }
        }catch (\Exception $e){
            return;
        }
    }
}
