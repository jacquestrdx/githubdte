<?php
/**
 * Created by PhpStorm.
 * User: jacquestredoux
 * Date: 2017/11/10
 * Time: 8:12 AM
 */
namespace App\Jacques;
use App\Statable;
class DeltaPowerLibrary
{
    public function PollviaSNMP($device){
        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.1.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['dc_volts'] = preg_split("/INTEGER: /", $result);
                $new_results['dc_volts'] = $new_results['dc_volts'][1];
            }
            $device->save();
        } catch (\Exception $e) {
            dd($e);
        }

        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.2.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['dc_load'] = preg_split("/INTEGER: /", $result);
                $new_results['dc_load'] = $new_results['dc_load'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.3.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['batt_curr'] = preg_split("/INTEGER: /", $result);
                $new_results['batt_curr'] = $new_results['batt_curr'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.4.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['batt_temp'] = preg_split("/INTEGER: /", $result);
                $new_results['batt_temp'] = $new_results['batt_temp'][1];

            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.5.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['charge_state'] = preg_split("/INTEGER: /", $result);
                $new_results['charge_state'] = $new_results['charge_state'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }
        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.6.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['current_limit'] = preg_split("/INTEGER: /", $result);
                $new_results['current_limit'] = $new_results['current_limit'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }

        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.7.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['rectifier_current'] = preg_split("/INTEGER: /", $result);
                $new_results['rectifier_current'] = $new_results['rectifier_current'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }
        $connections_oid_root = ".1.3.6.1.4.1.20246.2.3.1.1.1.2.3.8.0";
        try {
            snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
            $results = snmprealwalk($device->ip, $device->snmp_community, $connections_oid_root);
            foreach ($results as $result){
                $new_results['sytem_power'] = preg_split("/INTEGER: /", $result);
                $new_results['sytem_power'] = $new_results['sytem_power'][1];
            }
            $device->save();

        } catch (\Exception $e) {
        }
        dd($new_results);
    }
}