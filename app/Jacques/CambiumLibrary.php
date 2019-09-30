<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\DInterface;
use App\Fault;
use App\Interfacelog;
use App\Statable;
class CambiumLibrary
{

    public function pollviasnmp($device){
        $this->getCambiumDetails($device);
        $this->getCambiumWirelessStations($device);
    }

    public function getCambiumWirelessStations($device)
    {
        try {

            echo "Starting Stations \n";
            $connections_oid_root = "iso.3.6.1.4.1.17713.21.1.2.30.1";
            try {
                snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                $search = ".1.3.6.1.4.1.17713.21.1.2.30.1";
                $results = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root);
                foreach ($results as $key => $line) {
                    $newkey = preg_split("/$search/", $key);
                    $newnewkey = preg_split("/\./", $newkey['1'], 2);
                    $newarray[$newnewkey['1']][] = $line;
                }
                foreach ($newarray as $key => $item) {
                    $newkey = preg_split('/\./', $key);
                    $newkey = $newkey[1];
                    $final[$newkey][] = $item;
                }
            } catch (\Exception $e) {
            }
            if (ISSET($final)) {
                foreach ($final as $station) {
                    try {
                        $mac = preg_split("/STRING: /", $station['0']['0']);
                        if (array_key_exists("1", $mac)) {
                            $mac = strtoupper(preg_replace("/\"/", "", $mac['1'])) . ":";
                            $mac = substr($mac, 0, -1);
                            if (Statable::where('mac', '=', $mac)->exists()) {
                                try {
                                    $statable = Statable::where('mac', '=', $mac)->first();
                                    $statable->mac = $mac;
                                    $name = preg_split("/STRING: /", $station['17']['0']);
                                    $name = preg_replace('/"/', "", $name['1']);
                                    $name = preg_replace("/\s/", "", $name);
                                    $name = preg_replace('/_Uplink/', "", $name);
                                    $statable->name = $name ?? $statable->name = "N/A";
                                    $ip = preg_split("/IpAddress: /", $station['9']['0']);
                                    $statable->ip = $ip['1'] ?? $statable->ip = "N/A";
                                    $statable->latency = "N/A";
                                    $statable->ccq = "N/A";
                                    if (array_key_exists('28', $station)) {
                                        $distance = preg_split("/INTEGER: /", $station['28']['0']);
                                        $statable->distance = number_format(($distance['1'] / 1000), 2, '.', '');
                                    } else {
                                        $statable->distance = "0";
                                    }
                                    if (array_key_exists('35', $station)) {
                                        $model = preg_split("/STRING: /", $station['35']['0']);
                                        $statable->model = $model['1'] ?? $statable->model = NULL;
                                    } else {
                                        $statable->model = NULL;
                                    }
                                    if (array_key_exists('7', $station)) {
                                        $txrate = preg_split('/INTEGER: /', $station['7'][0]);
                                        $txrates = $txrate[1] ?? $txrates = 0;
                                    } else {
                                        $txrates = 0;
                                    }
                                    if (array_key_exists('8', $station)) {
                                        $rxrate = preg_split('/INTEGER: /', $station['8'][0]);
                                        $rxrates = $rxrate[1] ?? $rxrates = 0;
                                    } else {
                                        $rxrates = 0;
                                    }
                                    if (array_key_exists('6', $station)) {
                                        $rx_snr = preg_split('/INTEGER: /', $station['6'][0]);
                                        $rx_snr = $rx_snr[1] ?? $rx_snr = 0;
                                    } else {
                                        $rx_snr = 0;
                                    }
                                    if (array_key_exists('5', $station)) {
                                        $tx_snr = preg_split('/INTEGER: /', $station['5'][0]);
                                        $tx_snr = $tx_snr[1] ?? $tx_snr = 0;
                                    } else {
                                        $tx_snr = 0;
                                    }
                                    $statable->rx_snr = $rx_snr;
                                    $statable->tx_snr = $tx_snr;
                                    $signal1 = preg_split("/INTEGER: /", $station['4']['0']);
                                    $signal = $signal1['1'];
                                    $statable->signal = $signal ?? $statable->signal = "N/A";
                                    $statable->rates = $txrates . "/" . $rxrates;
                                    $statable->time = "N/A";
                                    $statable->device_id = $device->id;
                                    $statable->save();

                                    $data = array(
                                        "host" => $statable->id,
                                        "distance" => $statable->distance,
                                        "signal" => $statable->signal,
                                        "rx_snr" => $statable->rx_snr,
                                        "tx_snr" => $statable->tx_snr,
                                        "tx_rate" => $txrates,
                                        "rx_rate" => $rxrates,
                                    );
                                    if (!file_exists("/var/www/html/dte/rrd/cambiums/stations/" . trim($data['host']) . ".rrd")) {
                                        $this->createStationRRD($data);
                                    } else {
                                        $this->updateStationRRD($data);
                                    }

                                } catch (\Exception $e) {
                                }
                            } else {
                                $statable = new Statable();
                                $statable->mac = $mac;
                                $name = preg_split("/STRING: /", $station['17']['0']);
                                $statable->name = $name['1'] ?? $statable->name = "N/A";
                                $ip = preg_split("/IpAddress: /", $station['9']['0']);
                                $statable->ip = $ip['1'] ?? $statable->ip = "N/A";
                                $statable->latency = "N/A";
                                $statable->ccq = "N/A";
                                if (array_key_exists('35', $station)) {
                                    $model = preg_split("/STRING: /", $station['35']['0']);
                                    $statable->model = $model['1'] ?? $statable->model = NULL;
                                } else {
                                    $statable->model = NULL;
                                }
                                if (array_key_exists('7', $station)) {
                                    $txrate = preg_split('/INTEGER: /', $station['7'][0]);
                                    $txrates = $txrate[1] ?? $txrates = 0;
                                } else {
                                    $txrates = 0;
                                }
                                if (array_key_exists('8', $station)) {
                                    $rxrate = preg_split('/INTEGER: /', $station['8'][0]);
                                    $rxrates = $rxrate[1] ?? $rxrates = 0;
                                } else {
                                    $rxrates = 0;
                                }
                                if (array_key_exists('6', $station)) {
                                    $rx_snr = preg_split('/INTEGER: /', $station['6'][0]);
                                    $rx_snr = $rx_snr[1] ?? $rx_snr = 0;
                                } else {
                                    $rx_snr = 0;
                                }
                                if (array_key_exists('5', $station)) {
                                    $tx_snr = preg_split('/INTEGER: /', $station['5'][0]);
                                    $tx_snr = $tx_snr[1] ?? $tx_snr = 0;
                                } else {
                                    $tx_snr = 0;
                                }
                                $statable->rx_snr = $rx_snr;
                                $statable->tx_snr = $tx_snr;
                                $signal1 = preg_split("/INTEGER: /", $station['3']['0']);
                                $signal2 = preg_split("/INTEGER: /", $station['4']['0']);
                                $signal = ($signal1['1'] + $signal2['1']) / 2;
                                $statable->signal = $signal ?? $statable->signal = "N/A";

                                $distance = preg_split("/INTEGER: /", $station['28']['0']);
                                $statable->distance = round($distance['1'] / 1000, 2);
                                $statable->rates = $txrates . "/" . $rxrates;
                                $statable->time = "N/A";
                                $statable->device_id = $device->id;
                                $statable->save();
                                $data = array(
                                    "host" => $statable->id,
                                    "distance" => $statable->distance,
                                    "signal" => $statable->signal,
                                    "rx_snr" => $statable->rx_snr,
                                    "tx_snr" => $statable->tx_snr,
                                    "tx_rate" => $txrates,
                                    "rx_rate" => $rxrates,
                                );
                                if (!file_exists("/var/www/html/dte/rrd/cambiums/stations/" . trim($data['host']) . ".rrd")) {
                                    $this->createStationRRD($data);
                                } else {
                                    $this->updateStationRRD($data);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        dd($e);
                    }
                }

            }
//            $device->pollstatus = 1;
            $device->lastsnmpupdate = new \DateTime();
            $count = 0;
            $date = new \DateTime;
            $date->modify('-30 minutes');
            $formatted_date = $date->format('Y-m-d H:i:s');

            foreach ($device->statables as $station) {
                if ($station->updated_at > $formatted_date) {
                    $count++;
                }
            }
            $device->active_stations = $count;
            $device->save();

            if ($device->max_active_stations < $count) {
                $device->max_active_stations = $count;
            }
            $description = "Sector lost customers";
            if ($count < $device->active_stations * 0.8) {
                $fault = Fault::where('description', $description)->where('device_id', $device->id)->orderBy('updated_at', 'DESC')->first();
                if (isset($fault->status)) {
                    if ($fault->status == 0) {
                        //create new fault
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    } else {
                        //else do nothing
                    }
                }
            } else {
                $fault = Fault::where('description', $description)->where('device_id', $device->id)->orderBy('updated_at', 'DESC')->first();
                if (isset($fault)) {
                    $fault->status = 0;
                    $fault->save();
                    //set fault to resolved
                }
                //set fault to resolved
            }
        }catch (\Exception $e){
            dd($e);
        }
    }

    public function getCambiumDetails($device)
    {
        $connections_oid_root = "iso.3.6.1.4.1.17713.21.1.1.11.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $ssid = preg_split("/STRING: /", $result);
                $device->ssid = preg_replace("/\"/", "", $ssid['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }
        $connections_oid_root = "iso.3.6.1.4.1.17713.21.3.8.2.6";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $txpower = preg_split("/INTEGER: /", $result);
                $device->txpower = preg_replace("/\"/", "", $txpower['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }


        $connections_oid_root = "iso.3.6.1.4.1.17713.21.1.2.2";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $channel = preg_split("/INTEGER: /", $result);
                if($channel['1']=="1"){
                    $channel = "20";
                }else{
                    $channel ="40";
                }
                $device->channel = $channel;
            }
            $device->save();

        } catch (\Exception $e) {
        }


        $connections_oid_root = "iso.3.6.1.4.1.17713.21.1.2.1.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $ssid = preg_split("/INTEGER: /", $result);
                $device->freq = preg_replace("/\"/", "", $ssid['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }
    }

    public function createStationRRD($data){
        if(!file_exists("/var/www/html/dte/rrd/cambiums/stations/".trim($data['host']).".rrd")){
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:distance:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
                "DS:rx_snr:GAUGE:900:U:U",
                "DS:tx_snr:GAUGE:900:U:U",
                "DS:tx_rate:GAUGE:900:U:U",
                "DS:rx_rate:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );

            echo "CREATING RRD "."/var/www/html/dte/rrd/cambiums/stations/".trim($data['host']).".rrd\n";
            echo "CREATING RRD "."/var/www/html/dte/rrd/cambiums/stations/".trim($data['host']).".rrd\n";

            if(!\rrd_create("/var/www/html/dte/rrd/cambiums/stations/".trim($data['host']).".rrd",$options)){
                echo rrd_error();
            }
        }
    }

    public function updateStationRRD($data){
        $time= time();
        $rrdFile ="/var/www/html/dte/rrd/cambiums/stations/".trim($data['host']).".rrd";
        //\Log::info("Updating RRD for station ".$data['host']);
        $updator = new \RRDUpdater($rrdFile);
        $updator->update(array(
            "distance" => $data["distance"],
            "signal" => $data["signal"],
            "rx_snr" => $data["rx_snr"],
            "tx_snr" => $data["tx_snr"],
            "tx_rate" => $data["tx_rate"],
            "rx_rate" => $data["rx_rate"],
        ), $time);
    }
}