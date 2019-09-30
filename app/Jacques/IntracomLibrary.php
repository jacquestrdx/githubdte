<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\Statable;
use Composer\Repository\InstalledFilesystemRepository;

class IntracomLibrary
{
    public function getWirelessInfo($device){
        $this->getInfo($device);
        $this->getWirelessStations($device);
    }

    public function getWirelessStations($device){
        $count =0;
        echo "\nGetting stations\n";
        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.4.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.4.1.1807.112.1.4.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }


        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.3";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.4.1.1807.112.1.3.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $new_raw_array[$newnewkey['1']][] = $line;
            }

        } catch (\Exception $e) {
        }

        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.5.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = "1.3.6.1.4.1.1807.112.1.5.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $new_throughput_array[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }

        foreach($newarray as $key=> $array){
            foreach($array as $line){
                $finalarray[$key][] = $line;
            }
        }
        foreach($new_raw_array as $key=> $array){
            foreach($array as $line){
                $finalarray[$key][] = $line;
            }
        }
        foreach($new_throughput_array as $key=> $array){
            foreach($array as $line){
                $finalarray[$key][] = $line;
            }
        }
        foreach($finalarray as $station) {
            try {
                $mac = preg_split("/Hex-STRING: /", $station['21']);
                if (array_key_exists("1", $mac)) {
                    $mac = preg_replace('/ /', ':', $mac[1]);
                    $mac = substr($mac, 0, -1);

                    if (Statable::where('mac', '=', $mac)->exists()) {
                        $statable = Statable::where('mac', '=', $mac)->first();
                        $time = preg_split("/Timeticks: /", $station['2']);
                        $time = preg_split("/\)/", $time['1']);
                        $statable->time = $time['1'] ?? $statable->time = "N/A";
                        $uptime = preg_split("/Timeticks: /", $station['3']);
                        $uptime = preg_split("/\)/", $uptime['1']);
                        $statable->uptime = $uptime['1'] ?? $statable->uptime = "N/A";
                        if(trim($statable->time) =="0:00:00.00"){
                            $statable->distance = 0;
                            $statable->rxsignal = 0;
                            $statable->txsignal = 0;
                            $statable->rx_snr = 0;
                            $statable->tx_snr = 0;
                            $statable->rx_rate = 0;
                            $statable->tx_rate = 0;
                            $statable->tx_utilization = 0;
                            $statable->rx_utilization = 0;
                            $statable->rx_max_utilization = 0;
                            $statable->tx_max_utilization = 0;
                            $statable->disconnects = 0;
                            $statable->uptime = 0;
                            $statable->save();

                            $data = array(
                                "host" => $statable->id,
                                "distance" => 0,
                                "rxsignal" => 0,
                                "txsignal" => 0,
                                "disconnects" => 0,
                                "rx_snr" => 0,
                                "tx_snr" => 0,
                                "rx_rate" => 0,
                                "tx_rate" => 0,
                                "tx_utilization" => 0,
                                "rx_utilization" => 0,
                                "rx_max_utilization" => 0,
                                "tx_max_utilization" => 0
                            );
                            $value = 1;
//                            InfluxLibrary::writeToDB("dte","statables",$data,$value);
                            if(!file_exists("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd")){
                                IntracomLibrary::createStationRRD($data);
                            }else{
                                IntracomLibrary::updateStationRRD($data);
                            }
                        }else {
                            $count++;
                            $statable = Statable::where('mac', '=', $mac)->first();
                            $statable->mac = $mac ?? $statable->mac = "N/A";
                            $statable->name = "N/A";
                            $ip = preg_split("/STRING: /", $station['16']);
                            $statable->ip = preg_replace('/"/', '', $ip['1']) ?? $statable->ip = "N/A";
                            $statable->latency = "N/A";
                            $statable->ccq = "N/A";
                            $rxsignal = preg_split("/INTEGER: /", $station['8']);
                            $statable->rxsignal = $rxsignal['1'] ?? $statable->rxsignal = "N/A";
                            $txsignal = preg_split("/INTEGER: /", $station['11']);
                            $statable->txsignal = $txsignal['1'] ?? $statable->txsignal = "N/A";
                            $distance = preg_split("/INTEGER: /", $station['19']);
                            $statable->distance = round(($distance['1'] / 1000), 2);
                            $txrates = preg_split("/INTEGER: /", $station['5']);
                            $rxrates = preg_split("/INTEGER: /", $station['4']);
                            $statable->tx_rate = ($txrates['1']);
                            $statable->rx_rate = ($rxrates['1']);

                            $rx_snr = preg_split("/INTEGER: /", $station['9']);
                            $statable->rx_snr = $rx_snr[1];
                            $tx_snr = preg_split("/INTEGER: /", $station['12']);
                            $statable->tx_snr = $tx_snr[1];
                            if (array_key_exists('68', $station)) {
                                $rx_utilization = preg_split("/Gauge32: /", $station['68']);
                            }
                            $statable->rx_utilization = $rx_utilization['1'] ?? $statable->rx_utilization = 0;

                            if (array_key_exists('69', $station)) {
                                $tx_utilization = preg_split("/Gauge32: /", $station['69']);
                            }
                            $statable->tx_utilization = $tx_utilization['1'] ?? $statable->tx_utilization = 0;

                            if (array_key_exists('70', $station)) {
                                $max_rx_utilization = preg_split("/Gauge32: /", $station['70']);
                            }
                            $statable->rx_max_utilization = $max_rx_utilization['1'] ?? $statable->rx_max_utilization = 0;

                            if (array_key_exists('71', $station)) {
                                $max_tx_utilization = preg_split("/Gauge32: /", $station['71']);
                            }
                            $statable->tx_max_utilization = $max_tx_utilization['1'] ?? $statable->tx_max_utilization = 0;
                            if (array_key_exists('47', $station)) {
                                $disconnects = preg_split('/Counter64:/',$station['47']);
                            }
                            $statable->disconnects = trim($disconnects['1']);

                            if (array_key_exists('47', $station)) {
                                $disconnects = preg_split('/Counter64:/',$station['47']);
                            }
                            $statable->disconnects = trim($disconnects['1']);
                            $statable->device_id = $device->id;
                            $statable->save();
                            $data = array(
                                "host" => $statable->id,
                                "distance" => $statable->distance,
                                "rxsignal" => $statable->rxsignal,
                                "txsignal" => $statable->txsignal,
                                "disconnects" => $statable->disconnects,
                                "rx_snr" => $statable->rx_snr,
                                "tx_snr" => $statable->tx_snr,
                                "rx_rate" => $statable->rx_rate,
                                "tx_rate" => $statable->tx_rate,
                                "tx_utilization" => $statable->tx_utilization,
                                "rx_utilization" => $statable->rx_utilization,
                                "rx_max_utilization" => $statable->rx_max_utilization,
                                "tx_max_utilization" => $statable->tx_max_utilization
                            );
                            $value = 1;
                            if(!file_exists("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd")){
                                IntracomLibrary::createStationRRD($data);
                            }else{
                                IntracomLibrary::updateStationRRD($data);
                            }
                        }
                    } else {
                        $statable = new Statable();
                        $statable->mac = $mac ?? $statable->mac = "N/A";
                        $statable->name = "N/A";
                        $ip = preg_split("/STRING: /", $station['16']);
                        $statable->ip = preg_replace('/"/','',$ip['1']) ?? $statable->ip = "N/A";
                        $uptime = preg_split("/Timeticks: /", $station['3']);
                        $uptime = preg_split("/\)/", $uptime['1']);
                        $statable->uptime = $uptime['1'] ?? $statable->uptime = "N/A";
                        if(trim($statable->time) =="0:00:00.00"){
                            $statable->distance = 0;
                            $statable->rxsignal = 0;
                            $statable->txsignal = 0;
                            $statable->disconnects = 0;
                            $statable->rx_snr = 0;
                            $statable->tx_snr = 0;
                            $statable->rx_rate = 0;
                            $statable->tx_rate = 0;
                            $statable->tx_utilization = 0;
                            $statable->rx_utilization = 0;
                            $statable->rx_max_utilization = 0;
                            $statable->tx_max_utilization = 0;
                            if (array_key_exists('47', $station)) {
                                $disconnects = preg_split('/Counter64:/',$station['47']);
                            }
                            $statable->disconnects = trim($disconnects['1']);
                            $statable->save();

                            $data = array(
                                "host" => $statable->id,
                                "distance" => 0,
                                "rxsignal" => 0,
                                "txsignal" => 0,
                                "disconnects" => 0,
                                "rx_snr" => 0,
                                "tx_snr" => 0,
                                "rx_rate" => 0,
                                "tx_rate" => 0,
                                "tx_utilization" => 0,
                                "rx_utilization" => 0,
                                "rx_max_utilization" => 0,
                                "tx_max_utilization" => 0
                            );
                            $value = 1;
//                            InfluxLibrary::writeToDB("dte","statables",$data,$value);
                            if(!file_exists("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd")){
                                IntracomLibrary::createStationRRD($data);
                            }else{
                                IntracomLibrary::updateStationRRD($data);
                            }

                        }else {
                            $count++;
                            $statable->latency = "N/A";
                            $statable->ccq = "N/A";
                            $rxsignal = preg_split("/INTEGER: /", $station['8']);
                            $statable->rxsignal = $rxsignal['1'] ?? $statable->rxsignal = "N/A";
                            $txsignal = preg_split("/INTEGER: /", $station['11']);
                            $statable->txsignal = $txsignal['1'] ?? $statable->txsignal = "N/A";
                            $distance = preg_split("/INTEGER: /", $station['19']);
                            $statable->distance = round(($distance['1'] / 1000), 2);
                            $txrates = preg_split("/INTEGER: /", $station['5']);
                            $rxrates = preg_split("/INTEGER: /", $station['4']);
                            $statable->tx_rate = ($txrates['1']);
                            $statable->rx_rate = ($rxrates['1']);
                            $time = preg_split("/Timeticks: /", $station['2']);
                            $time = preg_split("/\)/", $time['1']);
                            $statable->time = $time['1'] ?? $statable->time = "N/A";
                            $rx_snr = preg_split("/INTEGER: /", $station['9']);
                            $statable->rx_snr = $rx_snr[1];
                            $tx_snr = preg_split("/INTEGER: /", $station['12']);
                            $statable->tx_snr = $tx_snr[1];

                            if(array_key_exists('69',$station)){
                                $tx_utilization = preg_split("/Gauge32: /", $station['69']);
                            }
                            $statable->tx_utilization = $tx_utilization['1'] ?? $statable->tx_utilization = 0;

                            if(array_key_exists('68',$station)){
                                $rx_utilization = preg_split("/Gauge32: /", $station['68']);
                            }
                            $statable->rx_utilization = $rx_utilization['1'] ?? $statable->rx_utilization = 0;

                            if(array_key_exists('70',$station)){
                                $max_rx_utilization = preg_split("/Gauge32: /", $station['70']);
                            }
                            $statable->rx_max_utilization = $max_rx_utilization['1'] ?? $statable->rx_max_utilization = 0;

                            if(array_key_exists('71',$station)){
                                $max_rx_utilization = preg_split("/Gauge32: /", $station['71']);
                            }
                            $statable->tx_max_utilization = $max_tx_utilization['1'] ?? $statable->tx_max_utilization = 0;
                            $statable->device_id = $device->id;
                            if (array_key_exists('47', $station)) {
                                $disconnects = preg_split('/Counter64:/',$station['47']);
                            }
                            $statable->disconnects = trim($disconnects['1']);
                            $statable->save();

                            $data = array(
                                "host" => $statable->id,
                                "distance" => $statable->distance,
                                "rxsignal" => $statable->rxsignal,
                                "txsignal" => $statable->txsignal,
                                "disconnects" => $statable->disconnects,
                                "rx_snr" => $statable->rx_snr,
                                "tx_snr" => $statable->tx_snr,
                                "rx_rate" => $statable->rx_rate,
                                "tx_rate" => $statable->tx_rate,
                                "tx_utilization" => $statable->tx_utilization,
                                "rx_utilization" => $statable->rx_utilization,
                                "rx_max_utilization" => $statable->rx_max_utilization,
                                "tx_max_utilization" => $statable->tx_max_utilization
                            );
                            $value = 1;
//                            InfluxLibrary::writeToDB("dte","statables",$data,$value);
                            if(!file_exists("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd")) {
                                IntracomLibrary::createStationRRD($data);
                            }else{
                                IntracomLibrary::updateStationRRD($data);
                            }
                        }
                    }
                }
            }catch (\Exception $e){
                dd($e);
            }
        }
        $device->active_stations = $count;
        $device->save();
    }

    public function getInfo($device){
        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.2.1.7";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $result = snmpwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $dl_util = preg_split('/Gauge32:/',$result['0']);
        } catch (\Exception $e) {
            echo $e;
        }
        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.2.1.8";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $result = snmpwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $ul_util = preg_split('/Gauge32:/',$result['0']);
        } catch (\Exception $e) {
            echo $e;
        }
        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.2.1.9";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $result = snmpwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $max_dl_util = preg_split('/Gauge32:/',$result['0']);
        } catch (\Exception $e) {
            echo $e;
        }
        $connections_oid_root = "iso.3.6.1.4.1.1807.112.1.2.1.10";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $result = snmpwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $max_ul_util = preg_split('/Gauge32:/',$result['0']);
        } catch (\Exception $e) {
            echo $e;
        }
        $device->dl_util = $dl_util['1'];
        $device->ul_util = $ul_util['1'];
        $device->max_dl_util = $max_dl_util['1'];
        $device->max_ul_util = $max_ul_util['1'];
        $device->save();
        $data = array(
            "host" => $device->id,
            "rx_utilization" => trim($device->dl_util),
            "tx_utilization" => trim($device->ul_util),
            "rx_max_utilization" => trim($device->max_dl_util),
            "tx_max_utilization" => trim($device->max_ul_util)
        );
        $value = 1;
//        InfluxLibrary::writeToDB("dte","intracoms",$data,$value);
        if(!file_exists("/var/www/html/dte/rrd/intracoms/".trim($data['host']).".rrd")) {
            IntracomLibrary::createInfoRRD($data);
        }else{
            IntracomLibrary::updateInfoRRD($data);
        }

        }

    public static function createStationRRD($data){
        if(!file_exists("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd")){
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:distance:GAUGE:900:U:U",
                "DS:rxsignal:GAUGE:900:U:U",
                "DS:txsignal:GAUGE:900:U:U",
                "DS:disconnects:GAUGE:900:U:U",
                "DS:rx_snr:GAUGE:900:U:U",
                "DS:tx_snr:GAUGE:900:U:U",
                "DS:rx_rate:GAUGE:900:U:U",
                "DS:tx_rate:GAUGE:900:U:U",
                "DS:tx_utilization:GAUGE:900:U:U",
                "DS:rx_utilization:GAUGE:900:U:U",
                "DS:rx_max_utilization:GAUGE:900:U:U",
                "DS:tx_max_utilization:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );

            echo "CREATING RRD "."/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd\n";

            if(!\rrd_create("/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd",$options)){
                echo rrd_error();
            }
        }
    }
    public static function updateStationRRD($data){
        $time= time();
            $rrdFile ="/var/www/html/dte/rrd/intracoms/stations/".trim($data['host']).".rrd";
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "distance" => $data["distance"],
                "rxsignal" => $data["rxsignal"],
                "txsignal" => $data["txsignal"],
                "disconnects" => $data["disconnects"],
                "rx_snr" => $data["rx_snr"],
                "tx_snr" => $data["tx_snr"],
                "tx_snr" => $data["tx_snr"],
                "rx_rate" => $data["rx_rate"],
                "tx_rate" => $data["tx_rate"],
                "tx_utilization" => $data["tx_utilization"],
                "rx_utilization" => $data["rx_utilization"],
                "tx_max_utilization" => $data["tx_max_utilization"],
                "rx_max_utilization" => $data["rx_max_utilization"],
            ), $time);
    }
    public static function createInfoRRD($data){
        if(!file_exists("/var/www/html/dte/rrd/intracoms/".trim($data['host']).".rrd")){
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:rx_utilization:GAUGE:900:U:U",
                "DS:tx_utilization:GAUGE:900:U:U",
                "DS:rx_max_utilization:GAUGE:900:U:U",
                "DS:tx_max_utilization:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );

            echo "CREATING RRD "."/var/www/html/dte/rrd/intracoms/".trim($data['host']).".rrd\n";

            if(!\rrd_create("/var/www/html/dte/rrd/intracoms/".trim($data['host']).".rrd",$options)){
                echo rrd_error();
            }
        }
    }
    public static function updateInfoRRD($data){
        $time= time();
        $rrdFile ="/var/www/html/dte/rrd/intracoms/".trim($data['host']).".rrd";
        echo "Updating RRD for infracom ".$data['host'];
        $updator = new \RRDUpdater($rrdFile);
        $updator->update(array(
            "tx_utilization" => $data["tx_utilization"],
            "rx_utilization" => $data["rx_utilization"],
            "tx_max_utilization" => $data["tx_max_utilization"],
            "rx_max_utilization" => $data["rx_max_utilization"],
        ), $time);
    }

}