<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\Device;
use App\Statable;
class MicroInstrument
{

    public static function updateAllMicroInstruments(){
        $microinstrument = new MicroInstrument();
        $devices = Device::where('devicetype_id','34')->get();
        foreach($devices as $device){
            $microinstrument->PollviaSNMP($device);
        }
    }

    public function PollviaSNMP($device){
        $connections_oid_root = "iso.3.6.1.4.1.45501.1.3.6.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $solar_charge = preg_split("/STRING: /", $result);
                $device->solar_charge = preg_replace("/\"/", "", $solar_charge['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = "iso.3.6.1.4.1.45501.1.3.5.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $volts = preg_split("/STRING: /", $result);
                $device->volts = preg_replace("/\"/", "", $volts['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = "iso.3.6.1.4.1.45501.1.3.1.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $batt1 = preg_split("/STRING: /", $result);
                $device->batt1 = preg_replace("/\"/", "", $batt1['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = "iso.3.6.1.4.1.45501.1.3.2.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $batt2 = preg_split("/STRING: /", $result);
                $device->batt2 = preg_replace("/\"/", "", $batt2['1']);
            }
            $device->save();

        } catch (\Exception $e) {
        }

    }
}