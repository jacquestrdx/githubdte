<?php

namespace App\Jacques;

use App\IP;
use App\BGPPeer;
use App\Acknowledgement;

class LigowaveLibrary
{
    public function getGenericChannel($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $channel       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.8.1.3.1.1.7.4.0");
        $channel       = preg_replace("/[^0-9]/","",$channel['.1.3.6.1.4.1.32750.3.8.1.3.1.1.7.4.0']);
        return $channel;
    }

    public function getGenericTXPower($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txpower       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.8.1.3.1.1.9.4.0");
        $txpower       = preg_split("/Gauge32: /", $txpower['.1.3.6.1.4.1.32750.3.8.1.3.1.1.9.4.0']);
        return $txpower['1'];
    }

    public function getGenericSsid($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $ssid       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.5.1.2.1.1.4.4");
        $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.32750.3.5.1.2.1.1.4.4']);
        return $ssid['1'];
    }

    public function getGenericTxRate($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txrate = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.5.1.2.1.1.11.4");
        $txrate       = preg_split("/Gauge32: /", $txrate['.1.3.6.1.4.1.32750.3.5.1.2.1.1.11.4']);
        return $txrate['1']/1000/1000;
    }

    public function getGenericTxSignal($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txsignal = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.8.1.3.1.1.75.4.0");
        $txsignal       = preg_split("/INTEGER: /", $txsignal['.1.3.6.1.4.1.32750.3.8.1.3.1.1.75.4.0']);
       return $txsignal['1'];
    }

    public function getGenericRxSignal($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $rxsignal = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.8.1.3.1.1.76.4.0");
        $rxsignal       = preg_split("/INTEGER: /", $rxsignal['.1.3.6.1.4.1.32750.3.8.1.3.1.1.76.4.0']);
        return  $rxsignal['1'];
    }


    public function getGenericFreq($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $freq       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.5.1.2.1.1.7.4");
        $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.32750.3.5.1.2.1.1.7.4']);
        return $freq['1'];
    }

    public function getRapidFireChannel($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $channel       = snmprealwalk($device->ip, "public", "iso.3.6.1.2.1.11.21.0");
        $channel       = preg_replace("/[^0-9]/","",$channel['.1.3.6.1.2.1.11.21.0']);
        return $channel;
    }

    public function getRapidFireTXPower($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txpower       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.8.1.3.1.1.9.4.0");
        $txpower       = preg_split("/Gauge32: /", $txpower['.1.3.6.1.4.1.32750.3.8.1.3.1.1.9.4.0']);
        return $txpower['1'];
    }

    public function getRapidFireSsid($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $ssid       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.5.1.2.1.1.4.4");
        $ssid       = preg_split("/STRING: /", $ssid['.1.3.6.1.4.1.32750.3.5.1.2.1.1.4.4']);
        return $ssid['1'];
    }

    public function getRapidFireTxRate($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txrate = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.5.1.2.1.1.11.4");
        $txrate       = preg_split("/Gauge32: /", $txrate['.1.3.6.1.4.1.32750.3.5.1.2.1.1.11.4']);
        return $txrate['1']/1000/1000;
    }

    public function getRapidFireTxSignal($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $txsignal = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.8.1.3.1.1.75.4.0");
        $txsignal       = preg_split("/INTEGER: /", $txsignal['.1.3.6.1.4.1.32750.3.8.1.3.1.1.75.4.0']);
        return $txsignal['1'];
    }

    public function getRapidFireRxSignal($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $rxsignal = snmprealwalk($device->ip, "public","iso.3.6.1.4.1.32750.3.8.1.3.1.1.76.4.0");
        $rxsignal       = preg_split("/INTEGER: /", $rxsignal['.1.3.6.1.4.1.32750.3.8.1.3.1.1.76.4.0']);
        return  $rxsignal['1'];
    }


    public function getRapidFireFreq($device){
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        $freq       = snmprealwalk($device->ip, "public", "iso.3.6.1.4.1.32750.3.5.1.2.1.1.7.4");
        $freq       = preg_split("/INTEGER: /", $freq['.1.3.6.1.4.1.32750.3.5.1.2.1.1.7.4']);
        return $freq['1'];
    }
}
