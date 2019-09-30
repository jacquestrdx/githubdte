<?php
namespace App\Jacques;

use App\Customsnmpoid;
use App\Ip;
use App\BGPPeer;
use App\Interfacelog;
use App\Statable;
use App\DInterface;
use function Sodium\add;

class CiscoLibrary
{
    public function PollviaSNMP($device){
        try{
            if($device->devicetype_id =="6"){
//                $this->getRouterInfo($device);
            }else{
//                $this->getSwitchInfo($device);
            }
        }catch (\Exception $e){
            echo $e;
        }
        //$this->CalculateThroughput($device);
    }

    public function getSwitchInfo($device){
        $connections_oid_root_cpu = "iso.3.6.1.4.1.9.9.109.1.1.1.1.5";
        $raw_connections_oid_root_cpu = ".1.3.6.1.4.1.9.9.109.1.1.1.1.5.7";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Cpu = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_cpu);
        } catch (\Exception $e) {
        }
        $CpuUsage = preg_split("/Gauge32: /", $results_Cpu[$raw_connections_oid_root_cpu]);
        $device->cpu = $CpuUsage[1] ?? $device->cpu = 0;
        $device->save();

        $connections_oid_root_memory= "iso.3.6.1.4.1.9.9.48.1.1.1.5";
        $raw_connections_oid_root_memory = ".1.3.6.1.4.1.9.9.48.1.1.1.5.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Mem_used = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_memory);
        } catch (\Exception $e) {
        }

        $memused = preg_split("/Gauge32: /", $results_Mem_used[$raw_connections_oid_root_memory]);
        $device->used_memory = $memused[1] ?? $device->used_memory = 0;
        $device->save();

        $connections_oid_root_memory= "iso.3.6.1.4.1.9.9.48.1.1.1.6";
        $raw_connections_oid_root_memory = ".1.3.6.1.4.1.9.9.48.1.1.1.6.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Mem_free = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_memory);
        } catch (\Exception $e) {
        }
        $memfree = preg_split("/Gauge32: /", $results_Mem_free[$raw_connections_oid_root_memory]);
        $device->free_memory = $memfree[1] ?? $device->free_memory = 0;
        $device->total_memory = $device->free_memory + $device->used_memory;
        $device->save();

        try{
            $uptime       = snmp2_real_walk($device->ip, $device->snmp_community, "iso.3.6.1.2.1.1.3");
            $uptime       = preg_split("/Timeticks: /", $uptime['.1.3.6.1.2.1.1.3.0']);
            $uptime       = preg_split("/\)/", $uptime['1']);
            $uptime = preg_replace('/\(/','',$uptime['0']) ?? $device->uptime = "N/A";
            $uptime = round($uptime/100,0);
        }catch (\Exception $e){
            echo $e."\n";
        }
        $device->uptime = $uptime;

        $connections_oid_root = "iso.3.6.1.2.1.47.1.1.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.47.1.1.1.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }
        foreach ($newarray as $value){
            foreach($value as $row){
                if(NULL!=strpos($row,'Temp')){
                    print_r($value);
                }
            }
        }
        //exit;
        $device->save();
    }

    public function getRouterInfo($device){
        $connections_oid_root_cpu = "iso.3.6.1.4.1.9.9.109.1.1.1.1.5";
        $raw_connections_oid_root_cpu = ".1.3.6.1.4.1.9.9.109.1.1.1.1.5.7";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Cpu = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_cpu);
        } catch (\Exception $e) {
        }
        $CpuUsage = preg_split("/Gauge32: /", $results_Cpu[$raw_connections_oid_root_cpu]);
        $device->cpu = $CpuUsage[1] ?? $device->cpu = 0;
        $device->save();

        $connections_oid_root_memory= "iso.3.6.1.4.1.9.9.48.1.1.1.5";
        $raw_connections_oid_root_memory = ".1.3.6.1.4.1.9.9.48.1.1.1.5.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Mem_used = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_memory);
        } catch (\Exception $e) {
        }

        $memused = preg_split("/Gauge32: /", $results_Mem_used[$raw_connections_oid_root_memory]);
        $device->used_memory = $memused[1] ?? $device->used_memory = 0;
        $device->save();

        $connections_oid_root_memory= "iso.3.6.1.4.1.9.9.48.1.1.1.6";
        $raw_connections_oid_root_memory = ".1.3.6.1.4.1.9.9.48.1.1.1.6.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results_Mem_free = snmp2_real_walk($device->ip, $device->snmp_community, $connections_oid_root_memory);
        } catch (\Exception $e) {
        }
        $memfree = preg_split("/Gauge32: /", $results_Mem_free[$raw_connections_oid_root_memory]);
        $device->free_memory = $memfree[1] ?? $device->free_memory = 0;
        $device->total_memory = $device->free_memory + $device->used_memory;
        $device->used_memory = round($device->used_memory / $device->total_memory * 100,2);
        $device->save();

        try{
            $uptime       = snmp2_real_walk($device->ip, $device->snmp_community, "iso.3.6.1.2.1.1.3");
            $uptime       = preg_split("/Timeticks: /", $uptime['.1.3.6.1.2.1.1.3.0']);
            $uptime       = preg_split("/\)/", $uptime['1']);
            $uptime = preg_replace('/\(/','',$uptime['0']) ?? $device->uptime = "N/A";
            $uptime = round($uptime/100,0);
        }catch (\Exception $e){
            echo $e."\n";
        }
        $device->uptime = $uptime;

        $connections_oid_root = "iso.3.6.1.2.1.47.1.1.1.1";

        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.47.1.1.1.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }

        $connections_oid_root = "iso.3.6.1.4.1.9.9.91.1.1.1.1";

        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.4.1.9.9.91.1.1.1.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }

        foreach($newarray as $key=> $value){
            if(sizeof($value)<18){
                unset($newarray[$key]);
            }
        }

        foreach($newarray as $row){
            if($row['17']=="INTEGER: 8"){
                $name = preg_replace('/ /','_',$row['5']);
                $name = preg_replace('/Temp:_/','_',$name);
                $name = preg_replace('/STRING:/','_',$name);
                $name = preg_replace('/\//','-',$name);
                $name = preg_replace('/__/','_',$name);
                $name = preg_replace('/\"/','_',$name);
                $name = preg_replace('/\'/','_',$name);
                $temps[$name] = $row['20'];
            }
        }
        foreach($temps as $key =>$temp){
            $temp = preg_replace('/INTEGER: /','',$temp);
            if (Customsnmpoid::where('value_name', '=', $key)->exists()) {
                $custom = Customsnmpoid::where('value_name',$key)->where('device_id',$device->id)->first();
                $custom->device_id = $device->id;
                $custom->value_name = $key;
                $custom->oid_to_poll = 0;
                $custom->snmp_community = 0;
                $custom->return_value = $temp;
                $custom->math = "*1";
                $custom->save();
            }else{
                $custom = new Customsnmpoid();
                $custom->device_id = $device->id;
                $custom->value_name = $key;
                $custom->oid_to_poll = 0;
                $custom->snmp_community = 0;
                $custom->return_value = $temp;
                $custom->math = "*1";
                $custom->save();
            }
            $rrdFile = "/var/www/html/dte/rrd/ciscos/custom/" . $key . ".rrd";
            if(!file_exists($rrdFile)){
                echo "NO RRD FOUND \n";
                $options = array(
                    '--step',config('rrd.step'),
                    "--start", "-1 day",
                    "DS:temperature:GAUGE:900:U:U",
                    "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
                );
                echo "CREATING RRD ".$rrdFile."\n";
                if(!\rrd_create($rrdFile,$options)){
                    echo rrd_error();
                }
            }else{
                $time = time();
                echo "Updating RRD ".$rrdFile." with $temp"."\n";
                $updator = new \RRDUpdater($rrdFile);
                $updator->update(array(
                    "temperature" => $temp
                ), $time);
            }

        }
        $newarray = array();

        $connections_oid_root = "iso.3.6.1.2.1.15.3.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.15.3.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($result as $key => $line) {
                $newkey = preg_split("/$search/", $key);
                $newnewkey = preg_split("/\./", $newkey['1'], 2);
                $newarray[$newnewkey['1']][] = $line;
            }
        } catch (\Exception $e) {
        }


        foreach ($newarray as $key=> $bgppeer){
            $remote_ip = preg_split('/IpAddress:/',$bgppeer[6]);
            $remote_as = preg_split('/INTEGER:/',$bgppeer[8]);
            $state = preg_split('/INTEGER:/',$bgppeer[1]);
            $statechanged = 0;
            if($state[1]=="1"){
                $state= "idle";
                $statechanged = 1;
            }
            if($state[1]=="2"){
                $state= "connect";
                $statechanged = 1;
            }
            if($state[1]=="3"){
                $state= "active";
                $statechanged = 1;
            }
            if($state[1]=="4"){
                $state= "opensent";
                $statechanged = 1;
            }
            if($state[1]=="5"){
                $state= "openconfirm";
                $statechanged = 1;
            }
            if($state[1]=="6"){
                $state= "established";
                $statechanged = 1;
            }
            if($statechanged==0){
                $state=$state[1];
                $state = preg_split('/\(/',$state);
                $state = $state[1];
            }

            $disabled = preg_split('/INTEGER:/',$bgppeer[2]);
            if($disabled[1]=="2"){
                $disabled = false;
            }else{
                $disabled = true;
            }
            $bgpuptime = preg_split('/Gauge32:/',$bgppeer[15]);
            $bgpuptime = $bgpuptime[1];

            if (BGPPeer::where('remote_address', '=', $remote_ip[1] )->exists()) {
                $bgppeer = BGPPeer::where('remote_address', '=', $remote_ip[1])->first();
                $bgppeer->remote_as = $remote_as[1];
                $bgppeer->remote_address = $remote_ip[1];
                $bgppeer->device_id = $device->id;
                $bgppeer->name = $remote_ip[1];
                $bgppeer->default_originate = 0;
                $bgppeer->state = $state;
                $bgppeer->prefix_count = "tba";
                $bgppeer->disabled = $disabled;
                $bgppeer->uptime = $bgpuptime;
                $bgppeer->save();

            } else {
                $bgppeer = new BGPPeer;
                $bgppeer->remote_as = $remote_as[1];
                $bgppeer->remote_address = $remote_ip[1];
                $bgppeer->device_id = $device->id;
                $bgppeer->name = $remote_ip[1];
                $bgppeer->default_originate = 0;
                $bgppeer->state = $state;
                $bgppeer->prefix_count = "tba";
                $bgppeer->disabled = $disabled;
                $bgppeer->uptime = $bgpuptime;
                $bgppeer->save();
            }
        }

        $connections_oid_root = "iso.3.6.1.2.1.4.20.1.1";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $search = ".1.3.6.1.2.1.15.3.1.";
            $result = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
        } catch (\Exception $e) {
        }

        foreach($result as $row){
            $ip = preg_split('/IpAddress:/',$row);
            $ips[] = $ip[1];
        }
        try{
            foreach($ips as $address){
                if (!IP::where('address', '=', trim($address))->where('device_id', '=', $device->id)->exists()) {
                    $ip = new Ip();
                    $ip->address = trim($address);
                    $ip->device_id = $device->id;
                    $ip->save();

                } else {
                    $ip = Ip::where('address', '=',trim($address))->first();
                    $ip->address = trim($address);
                    $ip->device_id = $device->id;
                    $ip->save();
                }
            }
        }catch (\Exception $e){
            dd($e);
        }

        $data = array(
            "cpu" => $device->cpu,
            "temp" => $device->temp,
            "memory" => $device->used_memory
        );
        $rrdFile = "/var/www/html/dte/rrd/ciscos/".trim($device->id).".rrd";
        if(!file_exists($rrdFile)){
            echo "NO RRD FOUND \n";
            $options = array(
                '--step',config('rrd.step'),
                "--start", "-1 day",
                "DS:cpu:GAUGE:900:U:U",
                "DS:temp:GAUGE:900:U:U",
                "DS:memory:GAUGE:900:U:U",
                "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
            );
            echo "CREATING RRD $rrdFile \n";

            if(!\rrd_create($rrdFile,$options)){
                echo rrd_error();
            }
        }else{
            $time = time();
            $updator = new \RRDUpdater($rrdFile);
            $updator->update(array(
                "cpu" => $data["cpu"],
                "temp" => $data["temp"],
                "memory" => $data["memory"],
            ), $time);
        }
        $date = new \DateTime;
        $formatted_date = $date->format('Y-m-d H:i:s');
        $device->lastsnmpupdate = $formatted_date;
        $device->save();
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
                        '--step',config('rrd.step'),
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
                    //\Log::info("Updating RRD for $dinterface->id");
                    $updator = new \RRDUpdater($rrdFile);
                    $updator->update(array(
                        "rxvalue" => $data["rxvalue"],
                        "txvalue" => $data["txvalue"],
                        "ifInErrors" => $data["ifInErrors"],
                        "ifOutErrors" => $data["ifOutErrors"],
                        "ifHCInUPkts" => $data['ifHCInUcastPkts'],
                        "ifHCOutUPkts" => $data['ifHCOutUcastPkts'],
                        "ifHCInMultiPkts" => $data['ifHCInMulticastPkts'],
                        "ifHCOutMultiPkts" => $data['ifHCOutMulticastPkts'],
                        "ifHCInBroadPkts" => $data['ifHCInBroadcastPkts'],
                        "ifHOutBroadPkts" => $data['ifHOutBroadcastPkts'],
                        "Availabilty" => $running
                    ), $time);
                }
            }
        }catch (\Exception $e){

        }
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

    public function StoreInterfaces($device){
        $interfaceresults = array();
        $interfaces = $this->getInterfaces($device);
        $this->storeInterfacesINDB($interfaces,$device);
    }

    public function storeInterfacesINDB($interfaces,$device){
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
                    $interfacelog->status = "$dinterface->name changed speed from $dinterface->previous_link_speed to $dinterface->link_speed";
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
                $dinterface->actual_mtu = $interfaceresult["MTU"];;
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
            $finals = array();
            $array = array();
            $rrdFile ="/var/www/html/dte/rrd/interfaces/".trim($interface->device_id)."/".trim($interface->default_name).".rrd";
            $result = rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-5000), "--end", time()-300 ) );
            foreach ( $result["data"]["rxvalue"] as $key => $value )
            {
                $labels[] = $key;
            }
            foreach ( $result["data"]["rxvalue"] as $key => $value )
            {
                $array['rxvalue'][] = $value;
            }
            foreach ( $result["data"]["Availabilty"] as $key => $value )
            {
                $array['availabilty'][] = $value;
            }
            foreach ( $result["data"]["txvalue"] as $key => $value )
            {
                $array['txvalue'][] = $value;
            }
            foreach ( $result["data"]["ifInErrors"] as $key => $value )
            {
                $array['ifInErrors'][] = $value;
            }
            foreach ( $result["data"]["ifOutErrors"] as $key => $value )
            {
                $array['ifOutErrors'][] = $value;
            }
            foreach ( $labels as $key => $value )
            {
                if(isset($labels[$key+1])){
                    $array['timestamps'][] = $labels[$key+1] - $value;
                }
            }
            foreach ($array['rxvalue'] as $key => $value){
                if(isset($array['rxvalue'][$key+1])) {
                    $rxvalue = $array['rxvalue'][$key + 1] - $value;
                    if ($rxvalue < 0) {
                        $finals['rxvalue'][] = round( ($rxvalue + 4294967294) * 8 / $array['timestamps'][$key]/1024/1024,2);
                    } else {
                        $finals['rxvalue'][] =  round( $rxvalue * 8 / $array['timestamps'][$key]/1024/1024,2);
                    }
                }
            }

            foreach ($array['txvalue'] as $key => $value){
                if(isset($array['txvalue'][$key+1])) {
                    $txvalue = $array['txvalue'][$key + 1] - $value;
                    if ($txvalue < 0) {
                        $finals['txvalue'][] = round( ($txvalue + 4294967294) * 8 / $array['timestamps'][$key]/1024/1024,2);
                    } else {
                        $finals['txvalue'][] =  round( $txvalue * 8 / $array['timestamps'][$key]/1024/1024,2);
                    }
                }
            }

            foreach($finals['txvalue'] as $key=> $value){
                if($key < sizeof($finals['txvalue'])){
                    $txvalue = $value;
                }
            }
            foreach($finals['rxvalue'] as $key=> $value){
                if($key < sizeof($finals['rxvalue'])){
                    $rxvalue = $value;
                }
            }


            if($interface->maxtxspeed < $txvalue){
                $interface->maxtxspeed = $txvalue;
            }
            if($interface->maxrxspeed < $rxvalue){
                $interface->maxrxspeed = $rxvalue;
            }

            $interface->txspeed = $txvalue;
            $interface->rxspeed = $rxvalue;
            $interface->save();
        }

    }


}