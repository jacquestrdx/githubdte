<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\Statable;
class SIAELibrary
{
    public function getWirelessInfo($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.39.1.1.12.1');
            $chain1       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$chain1)){
                $device->txsignal = $chain1['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.39.1.1.2.1');
            $txfreq       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$txfreq)){
                $device->txfreq = $txfreq['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.39.1.1.75.1');
            $rxfreq       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$rxfreq)){
                $device->rxfreq = $rxfreq['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.39.1.1.56.1');
            $output       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$output)){
                $device->txpower = $output['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }
        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.15.2.1.25.1');
            $output       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$output)){
                $device->txrate = $output['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }
        try{
            $result = snmpwalk($device->ip, 'SYSTEM','1.3.6.1.4.1.3373.25.15.2.1.27.1');
            $output       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$output)){
                $device->rxrate = $output['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }
        $device->signal = $device->txsignal;
        $device->save();

        $data = array(
            "txfreq" => $device->txfreq,
            "rxfreq" => $device->rxfreq,
            "txrate" => $device->txrate,
            "rxrate" => $device->rxrate,
            "signal" => $device->signal
        );
        $rrdFile = "/var/www/html/dte/rrd/siaes/".trim($device->id).".rrd";
        if (!file_exists($rrdFile)) {
            echo "NO RRD FOUND \n";
            $options = array(
                '--step', config('rrd.step'),
                "--start", "-1 day",
                "DS:txfreq:GAUGE:900:U:U",
                "DS:rxfreq:GAUGE:900:U:U",
                "DS:txrate:GAUGE:900:U:U",
                "DS:rxrate:GAUGE:900:U:U",
                "DS:signal:GAUGE:900:U:U",
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
                "txrate" => $data["txrate"],
                "rxrate" => $data["rxrate"],
                "signal" => $data["signal"],
            ), $time);
        }
    }
}