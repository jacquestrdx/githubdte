<?php

namespace App\Jacques;

use App\Ip;
use App\BGPPeer;
use App\Ghost;
use App\Pppoeclient;
use App\Acknowledgement;
use App\Deviceinterface;
use App\HistoricalPingWorker;
use App\Jacques\InfluxLibrary;
use App\Jacques\MacVendorsApi;
use App\Statable;
use App\User;

class UbntLibrary
{


    public function getUbntInfo($device){
        $device->ip = trim($device->ip);

        try {

            try {
                $soft = snmprealwalk($device->ip, $device->snmp_community, "iso.2.840.10036.3.1.2.1.4.5");
                $soft = preg_split("/STRING: /", $soft['.1.2.840.10036.3.1.2.1.4.5']);
                $soft = preg_split("/v/", $soft['1'], 2);
                $soft = preg_match("/^(?:[^\.]*\.){3}/", $soft['1'], $softv);
                $soft = trim($softv['0'], ".");
                $device->soft = $soft ?? $device->soft = "N/A";
            }catch(\Exception $e){

            }

            try {
                $freq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.4.1");
                $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.41112.1.4.1.1.4.1']);
                $device->freq = $freq['1'] ?? $device->freq = "N/A";
            }catch(\Exception $e){

            }

            try {
                $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.2");
                $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.4.5.1.2.1']);
                $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";
            }catch(\Exception $e){

            }

            try {
                $txpower       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.6.1");
                $txpower       = preg_split("/INTEGER: /", $txpower['.1.3.6.1.4.1.41112.1.4.1.1.6.1']);
                $device->txpower = $txpower['1'] ?? $device->txpower = "N/A";
            }catch(\Exception $e){

            }

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


            try {
                $wds = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.2");
                $wds = preg_split("/INTEGER: /", $wds['.1.3.6.1.4.1.41112.1.4.1.1.2.1']);
                $device->wds = $wds['1'] ?? $device->wds = "N/A";
            }catch(\Exception $e){

            }

            try {
                $airmaxq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.3.1");
                $airmaxq       = preg_split("/INTEGER: /", $airmaxq['.1.3.6.1.4.1.41112.1.4.6.1.3.1']);
                $device->airmaxq = $airmaxq['1'] ?? $device->airmaxq = "N/A";
            }catch(\Exception $e){

            }
            try {
                $airmaxc       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.4.1");
                $airmaxc       = preg_split("/INTEGER: /", $airmaxc['.1.3.6.1.4.1.41112.1.4.6.1.4.1']);
                $device->airmaxc = $airmaxc['1'] ?? $device->airmaxc = "N/A";
            }catch(\Exception $e){
            }
            try {
                $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.14.1");
                $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.4.5.1.14.1']);
                $device->channel = $channel['1'] ?? $device->channel = "N/A";
            }catch(\Exception $e){

            }

            try {
                $ccq           = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.7.1");
                $ccq           = preg_split("/INTEGER: /", $ccq['.1.3.6.1.4.1.41112.1.4.5.1.7.1']);
                $device->avg_ccq = $ccq['1'] ?? $device->avg_ccq = "N/A";
            }catch(\Exception $e){

            }

            try {
                $noise_floor           = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.8.1");
                $noise_floor           = preg_split("/INTEGER: /", $noise_floor['.1.3.6.1.4.1.41112.1.4.5.1.8.1']);
                $device->noise_floor= $noise_floor['1'] ?? $device->noise_floor = "N/A";
            }catch(\Exception $e){

            }
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


            try {
                $serial         = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.4.1");
                $serial         = preg_split("/Hex-STRING: /", $serial['.1.3.6.1.4.1.41112.1.4.5.1.4.1']);
                $device->previous_serial_nr = $device->serial_no;
                $device->serial_no = $serial['1'] ?? $device->serial_no = "N/A";
            }catch(\Exception $e){

            }
            \Session::flash('flash_message', 'Device successfully updated!');

        } catch (\Exception $e) {
            echo "UBNT SNMP try catch failed with " . $e;
//            $device->pollstatus = 0;
            $device->save();
            return;
        }
        try {
            echo "SSH into antenna \n";
            $connection = ssh2_connect($device->ip, 22);
            echo "SSH connected \n";
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.name/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->model = $result;
        }  catch (\Exception $e) {
            echo "UBNT ssh login failed with ". $device->md5_username."\n" ;
//            $device->pollstatus = 0;
            $device->save();
        }

        try {
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.name/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->model = $result;
        }  catch (\Exception $e) {
            echo "UBNT ssh login failed with ". "Bronberg \n" ;
//                $device->pollstatus = 0;
            $device->save();
        }

        echo "No errors occurred in UBNT try catch\n";
//        $device->pollstatus = 1;
        $device->lastsnmpupdate = new \DateTime();
        $count = 0;
        $date  = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        foreach ($device->statables as $station) {
            if ($station->updated_at > $formatted_date) {
                $count++;
            }
        }

        if ($device->max_active_stations < $count) {
            $device->max_active_stations = $count;
        }
        $device->active_stations = $count;
        $device->save();
    }

    public function getUbntWirelessStationsOld($device){
        $connections_oid_root = "iso.3.6.1.4.1.41112.1.4.7";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.4.1.41112.1.4.7.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
//            $device->pollstatus = 0;
            $device->save();
        } catch (\Exception $e) {
//            $device->pollstatus = 0;
            $device->save();
            return;
        }
        try{
            if (ISSET($newarray)) {
                foreach ($newarray as $station) {

                    $mac = preg_split("/Hex-STRING: /", $station['0']);
                    if (array_key_exists("1",$mac)) {

                        if (Statable::where('mac', '=', $mac['1'])->exists()) {
                            $statable = Statable::where('mac', '=', $mac['1'])->first();
                            $statable->mac = $mac['1'] ?? $statable->mac = "N/A";
                            $mac = preg_split("/Hex-STRING: /", $station['0']);
                            $statable->mac = $mac['1'] ?? $statable->mac = "N/A";
                            $name = preg_split("/STRING: /", $station['1']);
                            $name = preg_replace('/"/',"",$name['1']);
                            $name = preg_replace("/\s/","",$name);
                            $name = preg_replace('/_Uplink/',"",$name);
                            $statable->name = $name ?? $statable->name = "N/A";
                            $ip = preg_split("/IpAddress: /", $station['9']);
                            $statable->ip = $ip['1'] ?? $statable->ip = "N/A";
                            $latency = preg_split("/INTEGER: /", $station['6']);
                            $statable->latency = $latency['1'] ?? $statable->$latency = "N/A";
                            $ccq = preg_split("/INTEGER: /", $station['5']);
                            $statable->ccq = $ccq['1'] ?? $statable->ccq = "N/A";
                            $signal = preg_split("/INTEGER: /", $station['2']);
                            $statable->signal = $signal['1'] ?? $statable->signal = "N/A";
                            if($device->devicetype_id =="2"){
                                $statable->distance = "n/a";

                            }else{
                                $distance = preg_split("/INTEGER: /", $station['4']);
                                $statable->distance = round( ($distance['1']/1000),2);

                            }
                            $txrates = preg_split("/INTEGER: /", $station['10']);
                            $rxrates = preg_split("/INTEGER: /", $station['11']);
                            $statable->rates = ($txrates['1'] / 1000000) . "/" . ($rxrates['1'] / 1000000) ?? $statable->mac = "N/A";
                            $time = preg_split("/Timeticks: /", $station['14']);
                            $time = preg_split("/\)/", $time['1']);
                            $statable->time = $time['1'] ?? $statable->time = "N/A";
                            $statable->device_id = $device->id;
                            $statable->save();
                        } else {

                            $statable = new Statable;
                            $statable->mac = $mac['1'] ?? $statable->mac = "N/A";
                            $mac = preg_split("/Hex-STRING: /", $station['0']);
                            $statable->mac = $mac['1'] ?? $statable->mac = "N/A";
                            $name = preg_split("/STRING: /", $station['1']);
                            $name = preg_replace('/"/',"",$name['1']);
                            $name = preg_replace("/\s/","",$name);
                            $name = preg_replace('/_Uplink/',"",$name);
                            $statable->name = $name ?? $statable->name = "N/A";
                            $ip = preg_split("/IpAddress: /", $station['9']);
                            $statable->ip = $ip['1'] ?? $statable->ip = "N/A";
                            $latency = preg_split("/INTEGER: /", $station['6']);
                            $statable->latency = $latency['1'] ?? $statable->$latency = "N/A";
                            $ccq = preg_split("/INTEGER: /", $station['5']);
                            $statable->ccq = $ccq['1'] ?? $statable->ccq = "N/A";
                            $signal = preg_split("/INTEGER: /", $station['2']);
                            $statable->signal = $signal['1'] ?? $statable->signal = "N/A";
                            $distance = preg_split("/INTEGER: /", $station['4']);
                            $statable->distance = round( ($distance['1']/1000),2);
                            $txrates = preg_split("/INTEGER: /", $station['10']);
                            $rxrates = preg_split("/INTEGER: /", $station['11']);
                            $statable->rates = ($txrates['1'] / 1000000) . "/" . ($rxrates['1'] / 1000000) ?? $statable->mac = "N/A";
                            $time = preg_split("/Timeticks: /", $station['14']);
                            $time = preg_split("/\)/", $time['1']);
                            $statable->time = $time['1'] ?? $statable->time = "N/A";
                            $statable->device_id = $device->id;
                            $statable->save();
                        }
                    }

                }

            }
        }catch(\Exception $e){
            echo ($e)."\n";
        }
    }

    public function getUbntWirelessStations($device)
    {
        $results = "";
        try{
            $count = 0;
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'wstalist');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                $results .= $line;
            }
            $results = json_decode($results);
            foreach($results as $result){
                if (isset($result->mac)) {
                    if (Statable::where('mac', '=', $result->mac)->exists()) {
                        $count++;
                        $statable = Statable::where('mac', '=', $result->mac)->first();
                        $statable->mac = $result->mac ?? $statable->mac = "N/A";
                        $statable->name = $result->remote->hostname ?? $statable->name = "N/A";
                        $statable->ip = $result->lastip ?? $statable->ip = "N/A";
                        $statable->latency = $result->tx_latency ?? $statable->$latency = "N/A";
                        $statable->ccq = $result->ccq ?? $statable->ccq = "N/A";
                        $statable->signal = $result->remote->signal ?? $statable->signal = "N/A";
                        $statable->distance = round($result->distance/1000,2) ??  $statable->distance = 0;
                        $statable->time =  $result->remote->uptime ?? $statable->time = 00;
                        $statable->model = $result->remote->platform ??  $statable->model = "n/a";
                        $statable->device_id = $device->id;
                        $statable->save();
                        $statable->rates = ($result->rx ."/".$result->tx) ?? 00;
                        $cpu = $result->remote->cpuload ?? $cpu =0;
                        $noisefloor =  $result->remote->noisefloor ?? $noisefloor =0;
                        $txbytes = $result->remote->tx_bytes ?? $txbytes =0;
                        $rxbytes = $result->remote->rx_bytes ?? $rxbytes =0;
                        $txpower = $result->remote->tx_power ?? $txpower =0;
                        $rssi = $result->remote->rssi ?? $rssi =0;
                        $data = array(
                            "host" => $statable->id,
                            "distance" =>$statable->distance,
                            "signal" =>$rssi,
                            "rssi" => $rssi,
                            "txpower" => $txpower,
                            "cpu" => $cpu,
                            "noise_floor" => $noisefloor,
                            "tx_bytes" =>  $txbytes,
                            "rx_bytes" =>  $rxbytes,
                        );
                        if(!file_exists("/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd")){
                            $this->createStationRRD($data);
                        }else{
                            $this->updateStationRRD($data);
                        }
                    } else {
                        "Echo Creating new Station";
                        $count++;
                        $statable = new Statable;
                        $statable->mac = $result->mac ?? $statable->mac = "N/A";
                        $statable->name = $result->remote->hostname ?? $statable->name = "N/A";
                        $statable->ip = $result->lastip ?? $statable->ip = "N/A";
                        $statable->latency = $result->tx_latency ?? $statable->$latency = "N/A";
                        $statable->ccq = $result->ccq ?? $statable->ccq = "N/A";
                        $statable->signal = $result->remote->signal ?? $statable->signal = "N/A";
                        $statable->distance = round($result->distance/1000,2)  ??  $statable->distance = 0;
                        $statable->rates = ($result->rx ."/".$result->tx);
                        $statable->time =  $result->remote->uptime ?? $statable->time =0;
                        $statable->model = $result->remote->platform  ??  $statable->model = "n/a";
                        $statable->device_id = $device->id;
                        $statable->save();
                        $statable->rates = ($result->rx ."/".$result->tx) ?? 00;
                        $cpu = $result->remote->cpuload ?? $cpu =0;
                        $noisefloor =  $result->remote->noisefloor ?? $noisefloor =0;
                        $txbytes = $result->remote->tx_bytes ?? $txbytes =0;
                        $rxbytes = $result->remote->rx_bytes ?? $rxbytes =0;
                        $txpower = $result->remote->tx_power ?? $txpower =0;
                        $rssi = $result->remote->rssi ?? $rssi =0;
                        $data = array(
                            "host" => $statable->id,
                            "distance" =>$statable->distance,
                            "signal" =>$statable->signal,
                            "rssi" => $rssi,
                            "txpower" => $txpower,
                            "cpu" => $cpu,
                            "noise_floor" => $noisefloor,
                            "tx_bytes" =>  $txbytes,
                            "rx_bytes" =>  $rxbytes,
                        );
                        if(!file_exists("/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd")){
                            $this->createStationRRD($data);
                        }else{
                            $this->updateStationRRD($data);
                        }
                    }
                }
            }
        } catch (\Exception $e){
            echo $e;
//            $message = "Could not log into $device->ip $device->name \n </br>";
//            $message .= "I am using $device->md5_username and $device->md5_password \n </br>";
//            $message .= "http://dte.bronbergwisp.co.za/device/$device->id";
//            $subject = "DTE LOGIN FAILURE";
//            $user = User::find(152);
//            echo "Sending Mail to $user->email";
//            Mailer::sendMail($message,$subject,$user);
        }
        $device->active_stations = $count;
        $device->save();
    }

    public function getAirfibre($device){
        $device->ip = trim($device->ip);
        try{
            $chain1       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.2.1.11.1");
            $chain1       = preg_split("/INTEGER: /", $chain1['.1.3.6.1.4.1.41112.1.3.2.1.11.1']);
            $device->signal1 = $chain1['1'] ?? $device->signal1 = "N/A";
        }catch (\Exception $e){
            try{
                $chain1       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.4.1.16.120.138.32.95.196.66");
                $chain1       = preg_split("/INTEGER: /", $chain1['.1.3.6.1.4.1.41112.1.10.1.4.1.16.120.138.32.95.196.66']);
                $device->signal1 = $chain1['1'] ?? $device->signal1 = "N/A";
            }catch (\Exception $e){
            }
        }
        try{
            $chain2       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.2.1.14.1");
            $chain2       = preg_split("/INTEGER: /", $chain2['.1.3.6.1.4.1.41112.1.3.2.1.14.1']);
            $device->signal2 = $chain2['1'] ?? $device->signal2 = "N/A";
        }catch (\Exception $e){
            try{
                $chain2       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.4.1.6.120.138.32.95.196.66");
                $chain2       = preg_split("/INTEGER: /", $chain2['.1.3.6.1.4.1.41112.1.10.1.4.1.6.120.138.32.95.196.66']);
                $device->signal2 = $chain2['1'] ?? $device->signal2 = "N/A";
            }catch (\Exception $e){
            }        }


        try{
            $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.1.1.28.1");
            $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.3.1.1.28.1']);
            $device->channel = $channel['1'] ?? $device->channel = "N/A";
        }catch (\Exception $e){
            try{
                $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.2.4.0 ");
                $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.10.1.2.4.0']);
                $device->channel = $channel['1'] ?? $device->channel = "N/A";
            }catch (\Exception $e){

            }
        }

        try{
            $txpower       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.1.1.9");
            $txpower       = preg_split("/INTEGER: /", $txpower['.1.3.6.1.4.1.41112.1.3.1.1.9.1']);
            $device->txpower = $txpower['1'] ?? $device->txpower = "N/A";
        }catch (\Exception $e){
            echo $e."\n";
        }

        try{
            $txfreq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.1.1.5.1");
            $txfreq       = preg_split("/INTEGER: /", $txfreq['.1.3.6.1.4.1.41112.1.3.1.1.5.1']);
            $device->txfreq = $txfreq['1'] ?? $device->txfreq = "N/A";
        }catch (\Exception $e){
            try{
                $txfreq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.2.2.0");
                $txfreq       = preg_split("/INTEGER: /", $txfreq['.1.3.6.1.4.1.41112.1.10.1.2.2.0']);
                $device->txfreq = $txfreq['1'] ?? $device->txfreq = "N/A";
            }catch(\Exception $e){

            }
        }

        try{
            $rxfreq      = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.1.1.6.1");
            $rxfreq       = preg_split("/INTEGER: /", $rxfreq['.1.3.6.1.4.1.41112.1.3.1.1.6.1']);
            $device->rxfreq = $rxfreq['1'] ?? $device->rxfreq = "N/A";
        }catch (\Exception $e){
            try{
                $rxfreq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.2.2.0");
                $rxfreq       = preg_split("/INTEGER: /", $rxfreq['.1.3.6.1.4.1.41112.1.10.1.2.2.0']);
                $device->rxfreq = $rxfreq['1'] ?? $device->rxfreq = "N/A";
            }catch(\Exception $e){
            }
        }

        $device->freq = $device->rxfreq;

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


        try{
            $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.3.1.1.14.1");
            $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.3.1.1.14.1']);
            $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";
        }catch (\Exception $e){
            try{
                $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.10.1.2.5.0");
                $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.10.1.2.5.0']);
                $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";
            }catch (\Exception $e){

            }
        }

        try{
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.name/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->model = $result;
        } catch (\Exception $e){
        }

        $device->lastsnmpupdate = new \DateTime();

        $device->save();
    }

    public function getUbntAP($device){
        $device->ip = trim($device->ip);
        $this->getUbntWirelessStations($device);

        try {
            $freq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.4.1");
            $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.41112.1.4.1.1.4.1']);
            $device->freq = $freq['1'] ?? $device->freq = "N/A";

            $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.2");
            $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.4.5.1.2.1']);
            $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";

            $txpower       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.6.1");
            $txpower       = preg_split("/INTEGER: /", $txpower['.1.3.6.1.4.1.41112.1.4.1.1.6.1']);
            $device->txpower = $txpower['1'] ?? $device->txpower = "N/A";

            $wds       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.2");
            $wds       = preg_split("/INTEGER: /", $wds['.1.3.6.1.4.1.41112.1.4.1.1.2.1']);
            $device->wds = $wds['1'] ?? $device->wds = "N/A";

            $airmaxq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.3.1");
            $airmaxq       = preg_split("/INTEGER: /", $airmaxq['.1.3.6.1.4.1.41112.1.4.6.1.3.1']);
            $device->airmaxq = $airmaxq['1'] ?? $device->airmaxq = "N/A";

            $airmaxc       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.4.1");
            $airmaxc       = preg_split("/INTEGER: /", $airmaxc['.1.3.6.1.4.1.41112.1.4.6.1.4.1']);
            $device->airmaxc = $airmaxc['1'] ?? $device->airmaxc = "N/A";

            $txsignal       = snmprealwalk($device->ip ,$device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.5");
            $txsignal       = preg_split("/INTEGER: /", $txsignal['.1.3.6.1.4.1.41112.1.4.5.1.5.1']);
            $device->txsignal = $txsignal['1'] ?? $device->airmaxq = "N/A";


            $serial         = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.4.1");
            $serial         = preg_split("/INTEGER: /", $serial['.1.3.6.1.4.1.41112.1.4.5.1.4.1']);
            $device->previous_serial_nr = $device->serial_no;

            $device->serial_no = $serial['1'] ?? $device->serial_no = "N/A";

            try {
                $noise_floor           = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.8.1");
                $noise_floor           = preg_split("/INTEGER: /", $noise_floor['.1.3.6.1.4.1.41112.1.4.5.1.8.1']);
                $device->noise_floor= $noise_floor['1'] ?? $device->noise_floor = "N/A";
            }catch(\Exception $e){

            }
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


            $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.14.1");
            $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.4.5.1.14.1']);
            $device->channel = $channel['1'] ?? $device->channel = "N/A";
            $device->lastsnmpupdate = new \DateTime();

            \Session::flash('flash_message', 'Device successfully updated!');
            //dd($device);
        } catch (\Exception $e) {
            echo "UBNT SNMP try catch failed with " . $e;
//            $device->pollstatus = 0;
            $device->save();
            return;
        }

        try{
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.name/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->model = $result;
        } catch (\Exception $e){}

        echo "No errors occurred in UBNT try catch\n";
//        $device->pollstatus = 1;
        $device->save();
    }

    public function getUbntStation($device){
        $device->ip = trim($device->ip);

        try {
            $freq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.4.1");
            $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.41112.1.4.1.1.4.1']);
            $device->freq = $freq['1'] ?? $device->freq = "N/A";
            $device->save();

            $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.2");
            $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.4.5.1.2.1']);
            $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";
            $device->save();

            $txpower       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.6.1");
            $txpower       = preg_split("/INTEGER: /", $txpower['.1.3.6.1.4.1.41112.1.4.1.1.6.1']);
            $device->txpower = $txpower['1'] ?? $device->txpower = "N/A";
            $device->save();

            $signal = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.5.1");
            $signal       = preg_split("/INTEGER: /", $signal['.1.3.6.1.4.1.41112.1.4.5.1.5.1']);
            $device->signal = $signal['1'] ?? $device->signal = "N/A";
            $device->save();

            $wds       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.2");
            $wds       = preg_split("/INTEGER: /", $wds['.1.3.6.1.4.1.41112.1.4.1.1.2.1']);
            $device->wds = $wds['1'] ?? $device->wds = "N/A";
            $device->save();

            $airmaxq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.3.1");
            $airmaxq       = preg_split("/INTEGER: /", $airmaxq['.1.3.6.1.4.1.41112.1.4.6.1.3.1']);
            $device->airmaxq = $airmaxq['1'] ?? $device->airmaxq = "N/A";
            $device->save();

            $airmaxc       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.4.1");
            $airmaxc       = preg_split("/INTEGER: /", $airmaxc['.1.3.6.1.4.1.41112.1.4.6.1.4.1']);
            $device->airmaxc = $airmaxc['1'] ?? $device->airmaxc = "N/A";
            $device->save();

            try {
                $noise_floor           = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.8.1");
                $noise_floor           = preg_split("/INTEGER: /", $noise_floor['.1.3.6.1.4.1.41112.1.4.5.1.8.1']);
                $device->noise_floor= $noise_floor['1'] ?? $device->noise_floor = "N/A";
            }catch(\Exception $e){

            }
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


            $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.14.1");
            $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.4.5.1.14.1']);
            $device->channel = $channel['1'] ?? $device->channel = "N/A";
            $device->save();

            $serial         = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.4.1");
            $serial         = preg_split("/INTEGER: /", $serial['.1.3.6.1.4.1.41112.1.4.5.1.4.1']);
            $device->serial_no = $serial['1'] ?? $device->serial_no = "N/A";
            $device->save();

            // ssh get model

            \Session::flash('flash_message', 'Device successfully updated!');
            $device->lastsnmpupdate = new \DateTime();
        } catch (\Exception $e) {
            echo "UBNT SNMP try catch failed with " . $e;
//            $device->pollstatus = 0;
            $device->save();
            return;
        }
//        $device->pollstatus =1;
        $device->save();
    }

    public function getUbntACPrismSector($device){
        $device->ip = trim($device->ip);
        try{
            $device->getConnections();

        }catch (\Exception $e){

        }

        try {


            $freq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.4.1");
            $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.41112.1.4.1.1.4.1']);
            $device->freq = $freq['1'] ?? $device->freq = "N/A";

            $ssid       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.2");
            $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.41112.1.4.5.1.2.1']);
            $device->ssid = $ssid['1'] ?? $device->ssid = "N/A";

            $txpower       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.6.1");
            $txpower       = preg_split("/INTEGER: /", $txpower['.1.3.6.1.4.1.41112.1.4.1.1.6.1']);
            $device->txpower = $txpower['1'] ?? $device->txpower = "N/A";

            $wds       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.1.1.2");
            $wds       = preg_split("/INTEGER: /", $wds['.1.3.6.1.4.1.41112.1.4.1.1.2.1']);
            $device->wds = $wds['1'] ?? $device->wds = "N/A";

            $airmaxq       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.3.1");
            $airmaxq       = preg_split("/INTEGER: /", $airmaxq['.1.3.6.1.4.1.41112.1.4.6.1.3.1']);
            $device->airmaxq = $airmaxq['1'] ?? $device->airmaxq = "N/A";

            $airmaxc       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.6.1.4.1");
            $airmaxc       = preg_split("/INTEGER: /", $airmaxc['.1.3.6.1.4.1.41112.1.4.6.1.4.1']);
            $device->airmaxc = $airmaxc['1'] ?? $device->airmaxc = "N/A";

            try {
                $noise_floor           = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.8.1");
                $noise_floor           = preg_split("/INTEGER: /", $noise_floor['.1.3.6.1.4.1.41112.1.4.5.1.8.1']);
                $device->noise_floor= $noise_floor['1'] ?? $device->noise_floor = "N/A";
            }catch(\Exception $e){

            }
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


            $channel       = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.14.1");
            $channel       = preg_split("/INTEGER: /", $channel['.1.3.6.1.4.1.41112.1.4.5.1.14.1']);
            $device->channel = $channel['1'] ?? $device->channel = "N/A";
            $serial         = snmprealwalk($device->ip, $device->snmp_community, "iso.3.6.1.4.1.41112.1.4.5.1.4.1");
            $serial         = preg_split("/Hex-STRING: /", $serial['.1.3.6.1.4.1.41112.1.4.5.1.4.1']);
            $device->serial_no = $serial['1'] ?? $device->serial_no = "N/A";


            \Session::flash('flash_message', 'Device successfully updated!');

        } catch (\Exception $e) {
            echo "UBNT SNMP try catch failed with " . $e;
//            $device->pollstatus = 0;
            $device->save();
            return;
        }
        try{
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.name/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->model = $result;
        } catch (\Exception $e){}

        echo "No errors occurred in UBNT try catch\n";
//        $device->pollstatus = 1;
        $device->lastsnmpupdate = new \DateTime();
        $count = 0;
        $date  = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        foreach ($device->statables as $station) {
            if ($station->updated_at > $formatted_date) {
                $count++;
            }
        }

        if ($device->max_active_stations < $count) {
            $device->max_active_stations = $count;
        }


        $device->active_stations = $count;

        $device->save();
    }

    public function getSerialNumber($device){
        try{
            $connection = ssh2_connect($device->ip, 22);
            ssh2_auth_password($connection, $device->md5_username, $device->md5_password);
            $stream = ssh2_exec($connection, 'cat /var/etc/board.info');
            stream_set_blocking($stream, true);
            while($line = fgets($stream)) {
                flush();
                if (preg_match("/board.hwaddr/",$line)){
                    $results = preg_split("/\=/",$line);
                    $results = preg_split("/\n/",$results['1']);
                    $result = $results['0'];
                }
            }
            $device->serial_no = $result;
            $device->save();
            echo $device->serial_no." found for $device->name \n";

        } catch (\Exception $e){
        }

    }

    public function createStationRRD($data){
        if(!file_exists("/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd")){
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:distance:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
                "DS:txpower:GAUGE:900:U:U",
                "DS:rssi:GAUGE:900:U:U",
                "DS:noise_floor:GAUGE:900:U:U",
                "DS:cpu:GAUGE:900:U:U",
                "DS:tx_bytes:GAUGE:900:U:U",
                "DS:rx_bytes:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );

            echo "CREATING RRD "."/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd\n";

            if(!\rrd_create("/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd",$options)){
                echo rrd_error();
            }
        }
    }

    public function updateStationRRD($data){
        $time= time();
        $rrdFile ="/var/www/html/dte/rrd/ubnts/stations/".trim($data['host']).".rrd";
        //\Log::info("Updating RRD for station ".$data['host']);
        $updator = new \RRDUpdater($rrdFile);
        $updator->update(array(
            "distance" => $data["distance"],
            "signal" => $data["signal"],
            "txpower" => $data["txpower"],
            "rssi" => $data["rssi"],
            "noise_floor" => $data["noise_floor"],
            "cpu" => $data["cpu"],
            "tx_bytes" => $data["tx_bytes"],
            "rx_bytes" => $data["rx_bytes"]
        ), $time);
    }

}