<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\DInterface;
use App\Interfacelog;
use App\InterfaceWarning;
use App\Device;
use App\Statable;
class InterfacesLibrary
{
    public function doInterfaces($device){
        try{
            $this->StoreInterfaces($device);
        }catch (\Exception $e){
        }
        try{
            $this->CalculateThroughput($device);
        }catch (\Exception $e){
        }

        echo "Interfaces Done for $device->name \n";


        //$this->CalculateThroughput($device);
    }

    public function CalculateThroughput($device){
        $newresults = array();
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.6";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.6.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifInpuOct'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifInpuOct'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifInpuOct'][] = $line;
            }
        } catch (\Exception $e) {
        }

        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.10";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.10.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifOutOct'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifOutOct'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifOutOct'][]= $line;
            }
        } catch (\Exception $e) {
        }

        $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.20";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.20.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifOutErrors'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifOutErrors'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifOutErrors'][] = $line;
            }
        } catch (\Exception $e) {
        }

        $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.14";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.14.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifInErrors'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifInErrors'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifInErrors'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.7";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.7.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCInUcastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCInUcastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCInUcastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.11";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.11.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCOutUcastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCOutUcastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCOutUcastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.8";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.8.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCInMulticastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCInMulticastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCInMulticastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.12";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.12.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCOutMulticastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCOutMulticastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCOutMulticastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.9";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.9.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCInBroadcastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCInBroadcastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCInBroadcastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }
        $connections_oid_root_info = "iso.3.6.1.2.1.31.1.1.1.13";
        $raw_connections_oid_root_info = ".1.3.6.1.2.1.31.1.1.1.13.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results['ifHCOutBroadcastPkts'] = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            foreach ($results['ifHCOutBroadcastPkts'] as $key => $line) {
                $newkey = preg_split("/$raw_connections_oid_root_info/", $key);
                $newresults[$newkey['1']]['ifHCOutBroadcastPkts'][] = $line;
            }
        } catch (\Exception $e) {
        }

        foreach($newresults as $key=> $newresult){
            if(array_key_exists('ifHCInUcastPkts',$newresult)){
                $ifHCInUcastPkts = preg_split('/Counter64:/',$newresult['ifHCInUcastPkts'][0]);
            }else{
                $ifHCInUcastPkts[0] = 0;
                $ifHCInUcastPkts[1] = 0;
            }
            if(array_key_exists('ifHCOutUcastPkts',$newresult)){
                $ifHCOutUcastPkts = preg_split('/Counter64:/',$newresult['ifHCOutUcastPkts'][0]);
            }else{
                $ifHCOutUcastPkts[0] = 0;
                $ifHCOutUcastPkts[1] = 0;
            }
            if(array_key_exists('ifHCInMulticastPkts',$newresult)){
                $ifHCInMulticastPkts =  preg_split('/Counter64:/',$newresult['ifHCInMulticastPkts'][0]);
            }else{
                $ifHCInMulticastPkts[0] = 0;
                $ifHCInMulticastPkts[1] = 0;
            }
            if(array_key_exists('ifHCOutMulticastPkts',$newresult)){
                $ifHCOutMulticastPkts = preg_split('/Counter64:/',$newresult['ifHCOutMulticastPkts'][0]);
            }else{
                $ifHCOutMulticastPkts[0] = 0;
                $ifHCOutMulticastPkts[1] = 0;
            }
            if(array_key_exists('ifHCInBroadcastPkts',$newresult)){
                $ifHCInBroadcastPkts = preg_split('/Counter64:/',$newresult['ifHCInBroadcastPkts'][0]);
            }else{
                $ifHCInBroadcastPkts[0] = 0;
                $ifHCInBroadcastPkts[1] = 0;
            }
            if(array_key_exists('ifHCOutBroadcastPkts',$newresult)){
                $ifHCOutBroadcastPkts = preg_split('/Counter64:/',$newresult['ifHCOutBroadcastPkts'][0]);
            }else{
                $ifHCOutBroadcastPkts[0] = 0;
                $ifHCOutBroadcastPkts[1] = 0;
            }
            if(array_key_exists('ifInpuOct',$newresult)){
                $inoctets = preg_split('/Counter64:/',$newresult['ifInpuOct'][0]);
            }else{
                $inoctets[0] = 0;
                $inoctets[1] = 0;
            }
            if(array_key_exists('ifOutOct',$newresult)){
                $outoctets = preg_split('/Counter64:/',$newresult['ifOutOct'][0]);
            }else{
                $outoctets[0] = 0;
                $outoctets[1] = 0;
            }
            if(array_key_exists('ifOutErrors',$newresult)){
                $outerrors = preg_split('/Counter32:/',$newresult['ifOutErrors'][0]);
            }else{
                $outerrors[0] = 0;
                $outerrors[1] = 0;
            }
            if(array_key_exists('ifInErrors',$newresult)){
                $inerrors = preg_split('/Counter32:/',$newresult['ifInErrors'][0]);
            }else{
                $inerrors[0] = 0;
                $inerrors[1] = 0;
            }

            $interfaceresults[$key]['rxvalue'] = $inoctets[1];
            $interfaceresults[$key]['txvalue'] = $outoctets[1];
            $interfaceresults[$key]['ifInErrors'] = $inerrors[1];
            $interfaceresults[$key]['ifOutErrors'] = $outerrors[1];
            $interfaceresults[$key]['ifHCInUcastPkts'] = $ifHCInUcastPkts[1];
            $interfaceresults[$key]['ifHCOutUcastPkts'] = $ifHCOutUcastPkts[1];
            $interfaceresults[$key]['ifHCInMulticastPkts'] = $ifHCInMulticastPkts[1];
            $interfaceresults[$key]['ifHCOutMulticastPkts'] = $ifHCOutMulticastPkts[1];
            $interfaceresults[$key]['ifHCInBroadcastPkts'] = $ifHCInBroadcastPkts[1];
            $interfaceresults[$key]['ifHCOutBroadcastPkts'] = $ifHCOutBroadcastPkts[1];
        }

        try{
            foreach($interfaceresults as $key=> $item){
                $data = array(
                    "host" => $device->id,
                    "txvalue" => $item['txvalue'],
                    "rxvalue" => $item['rxvalue'],
                    "ifInErrors" => $item['ifInErrors'],
                    "ifOutErrors" => $item['ifOutErrors'],
                    "ifHCInUcastPkts" => $item['ifHCInUcastPkts'],
                    "ifHCOutUcastPkts" => $item['ifHCOutUcastPkts'],
                    "ifHCInMulticastPkts" => $item['ifHCInMulticastPkts'],
                    "ifHCOutMulticastPkts" => $item['ifHCOutMulticastPkts'],
                    "ifHCInBroadcastPkts" => $item['ifHCInBroadcastPkts'],
                    "ifHOutBroadcastPkts" => $item['ifHCOutBroadcastPkts'],
                    "iname" => $key
                );
                $value = 1;

//                InfluxLibrary::writeToDB("dte", "interfaces", $data, $value);
                if(!file_exists("/var/www/html/dte/rrd/interfaces/".trim($device->id)."/".trim($key).".rrd")){
                    echo "NO RRD FOUND \n";
                    $options = array(
                        '--step', config('rrd.step'),
                        "--start", "-1 day",
                        "DS:rxvalue:GAUGE:900:U:U",
                        "DS:txvalue:GAUGE:900:U:U",
                        "DS:ifInErrors:GAUGE:900:U:U",
                        "DS:ifOutErrors:GAUGE:900:U:U",
                        "DS:ifHCInUPkts:GAUGE:900:U:U",
                        "DS:ifHCOutUPkts:GAUGE:900:U:U",
                        "DS:ifHCInMultiPkts:GAUGE:900:U:U",
                        "DS:ifHCOutMultiPkts:GAUGE:900:U:U",
                        "DS:ifHCInBroadPkts:GAUGE:900:U:U",
                        "DS:ifHOutBroadPkts:GAUGE:900:U:U",
                        "DS:Availabilty:GAUGE:900:U:U",
                        "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
                    );
                    echo "CREATING RRD ".trim($device->id)."/".trim($key).".rrd \n";

                    $command = "mkdir /var/www/html/dte/rrd/interfaces/".$device->id;
                    exec($command);
                    if(!\rrd_create("/var/www/html/dte/rrd/interfaces/".trim($device->id)."/".trim($key).".rrd",$options)){
                        echo rrd_error();
                    }
                }else{
                    $rrdFile ="/var/www/html/dte/rrd/interfaces/".trim($device->id)."/".trim($key).".rrd";
                    $dinterface = DInterface::where('default_name', '=', $key)->where('device_id',$device->id)->first();
                    if($dinterface->running =="true"){
                        $running = 100;
                    }else{
                        $running = 0;
                    }
                    $time = time();
                    echo ("Updating $dinterface->id $rrdFile with tx value-".trim($data['txvalue'])."-\n");
                    $updator = new \RRDUpdater($rrdFile);
                    $updator->update(array(
                        "rxvalue" => trim($data["rxvalue"]),
                        "txvalue" => trim($data["txvalue"]),
                        "ifInErrors" => trim($data["ifInErrors"]),
                        "ifOutErrors" => trim($data["ifOutErrors"]),
                        "ifHCInUPkts" => trim($data['ifHCInUcastPkts']),
                        "ifHCOutUPkts" => trim($data['ifHCOutUcastPkts']),
                        "ifHCInMultiPkts" => trim($data['ifHCInMulticastPkts']),
                        "ifHCOutMultiPkts" => trim($data['ifHCOutMulticastPkts']),
                        "ifHCInBroadPkts" => trim($data['ifHCInBroadcastPkts']),
                        "ifHOutBroadPkts" => trim($data['ifHOutBroadcastPkts']),
                        "Availabilty" => trim($running)
                    ), $time);
                }
            }
        }catch (\Exception $e){
        }
    }


    public function StoreInterfaces($device){
        $interfaceresults = array();
        echo "Starting new Interfaces";
        try {
            $this->storeInterfacesINDB($device);
        }catch (\Exception $e){
            dd($e);
        }
    }

    public function storeInterfacesINDBOld($interfaces,$device){
        foreach($interfaces as $key=>$interface){
            try{
                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.5.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.5.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifInfo = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifspeed = preg_split("/Gauge32: /", $results_ifInfo[$raw_connections_oid_root_info]);
                $interfaceresults[$key]["ifSpeed"] = $ifspeed[1] ?? $interfaceresults[$key]["ifSpeed"] = 0;
                $interfaceresults[$key]["name"] = $interface;

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.4.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.4.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifMTU = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifMTU = preg_split("/INTEGER: /", $results_ifMTU[$raw_connections_oid_root_info]);
                $interfaceresults[$key]["MTU"] = $ifMTU[1] ?? $interfaceresults[$key]["MTU"] = "n/a";

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.6.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.6.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifMAC = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifMAC = preg_split("/Hex\-STRING: /", $results_ifMAC[$raw_connections_oid_root_info]);
                if(array_key_exists('1',$ifMAC)){
                    $interfaceresults[$key]["MAC"] = substr($ifMAC[1], 0, -1) ?? $interfaceresults[$key]["MAC"] = "n/a";
                }else{
                    $interfaceresults[$key]["MAC"] = "n/a";
                }

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.3.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.3.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifType = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifType = preg_split("/INTEGER: /", $results_ifType[$raw_connections_oid_root_info]);
                if($ifType[1]==53){
                    $ifType[1] = "Vlan";
                }
                if($ifType[1]==6){
                    $ifType[1] = "Ethernet";
                }
                if($ifType[1]==0){
                    $ifType[1] = "Other";
                }
                if($ifType[1]==157){
                    $ifType[1] = "Wireless";
                }
                $interfaceresults[$key]["Type"] = $ifType[1] ?? $interfaceresults[$key]["Type"] = "n/a";

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.7.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.7.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifAdmin = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }

                $ifAdmin = preg_split("/INTEGER: /", $results_ifAdmin[$raw_connections_oid_root_info]);
                if($ifAdmin[1]=="2"){
                    $ifAdmin = "true";
                }else{
                    $ifAdmin = "false";
                }
                $interfaceresults[$key]["disabled"] = $ifAdmin ?? $interfaceresults[$key]["disabled"] = "n/a";

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.8.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.8.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifRunning = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifRunning= preg_split("/INTEGER: /", $results_ifRunning[$raw_connections_oid_root_info]);
                if($ifRunning[1]=="1"){
                    $ifRunning = "true";
                }else{
                    $ifRunning = "false";
                }
                $interfaceresults[$key]["running"] = $ifRunning ?? $interfaceresults[$key]["running"] = "n/a";

                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.2.$key";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.2.".$key;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifDescr= snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $ifDescription= preg_split("/STRING: /", $results_ifDescr[$raw_connections_oid_root_info]);
                $interfaceresults[$key]["name"] = $ifDescription[1] ?? $interfaceresults[$key]["name"] = "n/a";

            }catch (\Exception $e){
                dd($e);
            }
        }
        foreach($interfaceresults as $index => $interfaceresult){
            $device_id = $device->id;
            if (DInterface::where('default_name', '=', $index)->where('device_id',$device->id)->exists()) {
                $dinterface = DInterface::where('default_name', '=', $index)->where('device_id',$device->id)->first();
                echo "$dinterface->name found \n";
                $dinterface->name = preg_replace('/"/','',$interfaceresult['name']);
                $dinterface->default_name = $index;
                $dinterface->mac_address = preg_replace('/ /', ':',$interfaceresult['MAC']);
                $dinterface->type = $interfaceresult['Type'];
                $dinterface->previous_running_state = $dinterface->running;
                $dinterface->running = $interfaceresult['running'];
                $dinterface->previous_link_speed = $dinterface->link_speed;
                $dinterface->link_speed = $interfaceresult['ifSpeed'];
                if ($dinterface->link_speed != $dinterface->previous_link_speed){
                    $interfacelog = new Interfacelog();
                    $interfacelog->device_id = $device->id;
                    $interfacelog->dinterface_id = $dinterface->id;
                    $interfacelog->status = "$dinterface->name changed speed from ".($dinterface->previous_link_speed)." to ".($dinterface->link_speed)."";
                    $interfacelog->save();
                }
                if ($dinterface->running != $dinterface->previous_running_state){
                    $interfacelog = new Interfacelog();
                    $interfacelog->device_id = $device->id;
                    $interfacelog->dinterface_id = $dinterface->id;
                    $interfacelog->status = "$dinterface->name changed status from $dinterface->previous_running_state to $dinterface->running";
                    $interfacelog->save();
                }
                $dinterface->last_link_down_time = "n/a";
                $dinterface->last_link_up_time = "n/a";
                $dinterface->mtu = $interfaceresult["MTU"];
                $dinterface->actual_mtu = $interfaceresult["MTU"];

                $dinterface->disabled = $interfaceresult['disabled'];
                $dinterface->device_id = $device_id;
                $dinterface->save();
                if($dinterface->running =="true"){
                    $running_nr = 100;
                }else{
                    $running_nr = 0;
                }
                $data = array(
                    "host" => $device->id,
                    "link_speed" => $dinterface->link_speed,
                    "up_time" => $running_nr,
                    "iname" => $dinterface->default_name
                );
                $value = 1;
//                InfluxLibrary::writeToDB("dte", "interfaces_status", $data, $value);
            } else {
                $dinterface = new DInterface();
                $dinterface->threshhold = round($interfaceresult['ifSpeed']/1000/1000,0);
                $dinterface->name = preg_replace('/"/','',$interfaceresult['name']);
                $dinterface->default_name = $index;
                $dinterface->mac_address = preg_replace('/ /', ':',$interfaceresult['MAC']);
                $dinterface->type = $interfaceresult['Type'];
                $dinterface->previous_running_state = $interfaceresult['running'];
                $dinterface->running = $interfaceresult['running'];
                $dinterface->previous_link_speed = $interfaceresult['ifSpeed'];
                $dinterface->link_speed = $interfaceresult['ifSpeed'];
                $dinterface->last_link_down_time = "n/a";
                $dinterface->last_link_up_time = "n/a";
                $dinterface->mtu = $interfaceresult['MTU'];
                $dinterface->actual_mtu =  $interfaceresult['MTU'];
                $dinterface->running =  $interfaceresult['running'];
                $dinterface->disabled =  $interfaceresult['disabled'];
                $dinterface->device_id = $device_id;
                $dinterface->save();
                $data = array(
                    "host" => $device->id,
                    "link_speed" => $dinterface->link_speed,
                    "up_time" => $running_nr,
                    "iname" => $dinterface->default_name
                );
                $value = 1;
//                InfluxLibrary::writeToDB("dte", "interfaces_status", $data, $value);
            }
        }

    }

    public function storeInterfacesINDB($device){
        $interfaceresults = array();
        $finals = array();
        $count = 1;
        while($count != 0) {
            try {
                $connections_oid_root = "iso.3.6.1.2.1.2.2.1.$count";
                snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                $result = snmp2_walk($device->ip, $device->snmp_community, $connections_oid_root);
                foreach ($result as $key => $line) {
                    $finals[$count][] = $line;
                }
                $count++;
            } catch (\Exception $e) {
                $count = 0;
            }
        }


        foreach ($finals as $rows){
            foreach($rows as $key=> $row){
                $interfaceresults[$key][] = $row;
            }
        }
        foreach($interfaceresults as $index => $interfaceresult){
            $count = 0;
            $device_id = $device->id;
            if (DInterface::where('default_name', '=', trim(preg_replace('/INTEGER: /','',$interfaceresult['0'])))->where('device_id',$device->id)->exists()) {
                $dinterface = DInterface::where('default_name', '=', trim(preg_replace('/INTEGER: /','',$interfaceresult['0'])))->where('device_id',$device->id)->first();
                if(strpos($interfaceresult['1'],'Hex-STRING:')!==false){
                    $name = preg_replace('/Hex-STRING: /','',$interfaceresult['1']);
                    $name = $this->hexToStr($name);
                }else{
                    $name = preg_replace('/"/','',$interfaceresult['1']);
                    $name = preg_replace('/STRING: / ','',$name);
                    $name = preg_replace('/"/','',$name);
                }
                $dinterface->name = $name;
                $dinterface->default_name = trim(preg_replace('/INTEGER: /','',$interfaceresult['0']));
                $mac = preg_replace('/Hex-STRING: /','',$interfaceresult['5']);
                $mac =  preg_replace('/ /', ':',$mac);
                $mac = substr($mac, 0, -1);
                $dinterface->mac_address = $mac;
                $typeid = preg_replace('/INTEGER: /','',$interfaceresult['2']);
                $dinterface->type = $this->getType($typeid);
                $ifRunning = preg_replace('/INTEGER: /','',$interfaceresult['6']);
                if($ifRunning=="1"){
                    $ifRunning = "true";
                }else{
                    $ifRunning = "false";
                }
                $dinterface->previous_running_state = $dinterface->running;
                $dinterface->running = $ifRunning;
                $dinterface->previous_link_speed = $dinterface->link_speed;

                $dinterface->link_speed = preg_replace('/Gauge32: /','',$interfaceresult['4']);
                if ($dinterface->link_speed != $dinterface->previous_link_speed){
                    $interfacelog = new Interfacelog();
                    $interfacelog->device_id = $device->id;
                    $interfacelog->dinterface_id = $dinterface->id;
                    $interfacelog->status = "$dinterface->name changed speed from ".($dinterface->previous_link_speed)." to ".($dinterface->link_speed)."";
                    $interfacelog->save();
                }
                if ($dinterface->running != $dinterface->previous_running_state){
                    $interfacelog = new Interfacelog();
                    $interfacelog->device_id = $device->id;
                    $interfacelog->dinterface_id = $dinterface->id;
                    $interfacelog->status = "$dinterface->name changed status from $dinterface->previous_running_state to $dinterface->running";
                    $interfacelog->save();
                }
                $dinterface->last_link_down_time = "n/a";
                $dinterface->last_link_up_time = "n/a";
                $dinterface->mtu = preg_replace('/INTEGER: /','',$interfaceresult['3']);
                $dinterface->actual_mtu = preg_replace('/INTEGER: /','',$interfaceresult['3']);
                $ifAdmin = preg_replace('/INTEGER: /','',$interfaceresult['7']);
                if($ifAdmin=="2"){
                    $ifAdmin = "true";
                }else{
                    $ifAdmin = "false";
                }
                $dinterface->disabled = $ifAdmin;
                $dinterface->device_id = $device_id;
                $dinterface->save();
            }
            else {
                $dinterface = new DInterface();
                $name = preg_replace('/"/','',$interfaceresult['1']);
                $name = preg_replace('/STRING:/ ','',$name);
                $dinterface->name = preg_replace('/"/','',$interfaceresult['1']);
                $dinterface->default_name =trim(preg_replace('/INTEGER: /','',$interfaceresult['0']));
                $mac = preg_replace('/Hex-STRING: /','',$interfaceresult['5']);
                $mac =  preg_replace('/ /', ':',$mac);
                $mac = substr($mac, 0, -1);
                $dinterface->mac_address = $mac;
                $typeid = preg_replace('/INTEGER: /','',$interfaceresult['2']);
                $dinterface->type = $this->getType($typeid);
                $ifRunning = preg_replace('/INTEGER: /','',$interfaceresult['6']);
                if($ifRunning=="1"){
                    $ifRunning = "true";
                }else{
                    $ifRunning = "false";
                }
                $dinterface->previous_running_state = $ifRunning;
                $dinterface->running = $ifRunning;
                $dinterface->previous_link_speed = preg_replace('/Gauge32: /','',$interfaceresult['4']);
                $dinterface->link_speed = preg_replace('/Gauge32: /','',$interfaceresult['4']);
                $dinterface->last_link_down_time = "n/a";
                $dinterface->last_link_up_time = "n/a";
                $dinterface->mtu = preg_replace('/INTEGER: /','',$interfaceresult['3']);
                $dinterface->actual_mtu = preg_replace('/INTEGER: /','',$interfaceresult['3']);
                $ifAdmin = preg_replace('/INTEGER: /','',$interfaceresult['7']);
                if($ifAdmin=="2"){
                    $ifAdmin = "true";
                }else{
                    $ifAdmin = "false";
                }
                $dinterface->disabled = $ifAdmin;
                $dinterface->device_id = $device_id;
                $dinterface->save();
                $value = 1;
//                InfluxLibrary::writeToDB("dte", "interfaces_status", $data, $value);
            }
        }
        echo "\n Interfaces Done \n";
    }

    public function getInterfaces($device){
        $final_results_ifIndex = array();
        $connections_oid_root_names = "iso.3.6.1.2.1.31.1.1.1.1";
        $raw_connections_oid_root_names = ".1.3.6.1.2.1.31.1.1.1.1.";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_ifName = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_names);
        } catch (\Exception $e) {
        }
        $connections_oid_root_index = "iso.3.6.1.2.1.2.2.1.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_ifIndex = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_index);
        } catch (\Exception $e) {
        }

        foreach($results_ifIndex as $key=> $result_ifIndex){
            if (strpos($result_ifIndex,'INTEGER') !== false){
                $final  = preg_split("/INTEGER: /", $result_ifIndex);
            }
            $result_ifName = preg_split("/STRING: /",$results_ifName[$raw_connections_oid_root_names.$final[1]]);
            $final_results_ifIndex[$final[1]] = $result_ifName[1];
        }

        return $final_results_ifIndex;
    }

    public function syncInterfaces($device)
    {
        $interfaces = DInterface::where('device_id', $device->id)->where('type', '!=', "Null0")->get();
        foreach ($interfaces as $interface) {
            try {
                $finals = array();
                $array = array();
                $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($interface->device_id) . "/" . trim($interface->default_name) . ".rrd";
                $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time() - 5000), "--end", time() - 300));
                if($result){
                    foreach ($result["data"]["rxvalue"] as $key => $value) {
                        $labels[] = $key;
                    }
                    foreach ($result["data"]["rxvalue"] as $key => $value) {
                        $array['rxvalue'][] = $value;
                    }
                    foreach ($result["data"]["Availabilty"] as $key => $value) {
                        $array['availabilty'][] = $value;
                    }
                    foreach ($result["data"]["txvalue"] as $key => $value) {
                        $array['txvalue'][] = $value;
                    }
                    foreach ($result["data"]["ifInErrors"] as $key => $value) {
                        $array['ifInErrors'][] = $value;
                    }
                    foreach ($result["data"]["ifOutErrors"] as $key => $value) {
                        $array['ifOutErrors'][] = $value;
                    }
                    foreach ($labels as $key => $value) {
                        if (isset($labels[$key + 1])) {
                            $array['timestamps'][] = $labels[$key + 1] - $value;
                        }
                    }
                    foreach ($array['rxvalue'] as $key => $value) {
                        if (isset($array['rxvalue'][$key + 1])) {
                            if (($array['rxvalue'][$key + 1] == 0) or ($value == 0)) {
                                $finals['rxvalue'][] = 0;
                            } else {
                                $rxvalue = $array['rxvalue'][$key + 1] - $value;
                                $final = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                                $finals['rxvalue'][] = $final;

                            }
                        }
                    }

                    foreach ($array['txvalue'] as $key => $value) {
                        if (isset($array['txvalue'][$key + 1])) {
                            if (($array['txvalue'][$key + 1] == 0) or ($value == 0)) {
                                $finals['txvalue'][] = 0;
                            } else {
                                $rxvalue = $array['txvalue'][$key + 1] - $value;
                                $finals['txvalue'][] = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                            }
                        }
                    }

                    foreach ($finals['txvalue'] as $key => $value) {
                        if ($key < sizeof($finals['txvalue'])) {
                            if (is_finite($value)) {
                                $txvalue = $value;
                            } else {
                                $txvalue = 0;
                            }
                        }
                    }
                    foreach ($finals['rxvalue'] as $key => $value) {
                        if ($key < sizeof($finals['rxvalue'])) {
                            if (is_finite($value)) {
                                $rxvalue = $value;
                            } else {
                                $rxvalue = 0;
                            }
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
                    $date = new \DateTime;
                    $date->modify('-30 minutes');
                    $formatted_date = $date->format('Y-m-d H:i:s');
                    if ($interface->threshhold == 0) {

                    } else {

                        if ($interface->created_at < $formatted_date) {
                            echo $interface->device->name . " -- " . $interface->name . " using " . $interface->txspeed . " out of " . $interface->threshhold . "\n";
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
                }else{
                    echo $interface->name."\n";
                }

            }catch (\Exception $e){
                echo $e;
            }
        }

    }

    public function getType($id){
        if($id==53){
            return "Vlan";
        }
        if($id==6){
            return "Ethernet";
        }
        if($id==0){
            return  "Other";
        }
        if($id==157){
            return "Wireless";
        }
        return "Other";
    }
    public function CalculateThroughputOld($device){
        $throuput = array();
        $interfaces = DInterface::where('device_id',$device->id)->where('type','!=',"Null0")->get();
        ////RX
        foreach ($interfaces as $interface){
            $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.10.$interface->default_name";
            $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.10.".$interface->default_name;
            try {
                snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                $results_ifInpuOct = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            } catch (\Exception $e) {
            }

            $time = time();
            try{
                $ifInpuOct = preg_split("/Counter32: /", $results_ifInpuOct[$raw_connections_oid_root_info]) ;
                $interfaceresults[$interface->default_name]["rxvalue"][] = [$time ,($ifInpuOct[1]),$interface->link_speed] ?? $interfaceresults[$interface->default_name]["rxvalue"]= [$time,0,$interface->link_speed];
            }catch (\Exception $e){
                $interfaceresults[$interface->default_name]["rxvalue"][] = [$time ,0,$interface->link_speed];
            }
        }
        ///TX
        foreach ($interfaces as $interface){
            $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.16.$interface->default_name";
            $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.16.".$interface->default_name;
            try {
                snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                $results_ifOutOct = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
            } catch (\Exception $e) {
            }
            $time = time();
            try{
                $ifOutOct = preg_split("/Counter32: /", $results_ifOutOct[$raw_connections_oid_root_info]) ?? $ifOutOct['0'][0]= 0;
                $interfaceresults[$interface->default_name]["txvalue"][] = [$time ,($ifOutOct[1]),$interface->link_speed] ?? $interfaceresults[$interface->default_name]["txvalue"]= [$time,0,$interface->link_speed];
            }catch (\Exception $e){
                $interfaceresults[$interface->default_name]["txvalue"][] = [$time,0,$interface->link_speed];
            }
        }

        //////ifOutErrors
        foreach ($interfaces as $interface){
            if( ($interface->type =="135") or($interface->type =="166")) {
                $time = time();
                $interfaceresults[$interface->default_name]["ifOutErrors"][] = [$time ,(0),$interface->link_speed];
            }else{
                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.20.$interface->default_name";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.20.".$interface->default_name;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifOutErrors = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $time = time();
                $ifOutErrors = preg_split("/Counter32: /", $results_ifOutErrors[$raw_connections_oid_root_info]) ?? $ifOutErrors[1] = 0;
                $interfaceresults[$interface->default_name]["ifOutErrors"][] = [$time ,($ifOutErrors[1]),$interface->link_speed] ?? $interfaceresults[$interface->default_name]["ifOutErrors"]= [$time,0,$interface->link_speed];
            }
        }

        //////ifInErrors
        foreach ($interfaces as $interface){
            if( ($interface->type =="135") or($interface->type =="166")) {
                $time = time();
                $interfaceresults[$interface->default_name]["ifInErrors"][] = [$time ,(0),$interface->link_speed];
            }else{
                $connections_oid_root_info = "iso.3.6.1.2.1.2.2.1.14.$interface->default_name";
                $raw_connections_oid_root_info = ".1.3.6.1.2.1.2.2.1.14.".$interface->default_name;
                try {
                    snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
                    $results_ifInErrors = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_info);
                } catch (\Exception $e) {
                }
                $time = time();
                $ifInErrors = preg_split("/Counter32: /", $results_ifInErrors[$raw_connections_oid_root_info]);
                $interfaceresults[$interface->default_name]["ifInErrors"][] = [$time ,($ifInErrors[1]),$interface->link_speed] ?? $interfaceresults[$interface->default_name]["ifInErrors"]= [$time,0,$interface->link_speed];
            }
        }


        ///CALCULATE
        try{

            foreach($interfaceresults as $index => $interfaceresult){
                $inoctets = $interfaceresult['rxvalue'][0][1];
                $outoctets = $interfaceresult['txvalue'][0][1];
                $throuput[$index]["rxvalue"] = $inoctets;
                $throuput[$index]["txvalue"] = $outoctets;
                $throuput[$index]["ifOutErrors"] =  $interfaceresult['ifOutErrors'][0][1];
                $throuput[$index]["ifInErrors"] =  $interfaceresult['ifInErrors'][0][1];
            }
            foreach($throuput as $key=> $item){
                $data = array(
                    "host" => trim($device->id),
                    "txvalue" => trim($item['txvalue']),
                    "rxvalue" => trim($item['rxvalue']),
                    "ifInErrors" => trim($item['ifInErrors']),
                    "ifOutErrors" => trim($item['ifOutErrors']),
                    "iname" => trim($key)
                );
                $value = 1;
//                InfluxLibrary::writeToDB("dte", "interfaces", $data, $value);

            }

        }catch (\Exception $e){

        }
    }


    function hexToStr($hex){
        $hex = preg_replace('/ /','',$hex);
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}