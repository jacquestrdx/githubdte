<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\Statable;
class AviatLibrary
{
    public function getWirelessInfo($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        try{
            $result = snmp2_walk($device->ip, $device->snmp_community,'1.3.6.1.4.1.3373.25.39.1.1.12.1');
            $chain1       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$chain1)){
                $device->txsignal = $chain1['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmp2_walk($device->ip, $device->snmp_community,'1.3.6.1.4.1.3373.25.39.1.1.2.1');
            $txfreq       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$txfreq)){
                $device->txfreq = $txfreq['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmp2_walk($device->ip, $device->snmp_community,'1.3.6.1.4.1.3373.25.39.1.1.75.1');
            $rxfreq       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$rxfreq)){
                $device->rxfreq = $rxfreq['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }

        try{
            $result = snmp2_walk($device->ip, $device->snmp_community,'1.3.6.1.4.1.3373.25.39.1.1.56.1');
            $output       = preg_split("/INTEGER: /", $result['0']);
            if(array_key_exists('1',$output)){
                $device->txpower = $output['1'];
                $device->save();
            }
        }catch (\Exception $e){
        }


        dd($device->txsignal." - ".$device->txfreq." - ".$device->rxfreq." - ".$device->output_power);
    }
}