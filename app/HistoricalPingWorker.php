<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Acknowledgement;
use Illuminate\Support\Facades\Log;

class HistoricalPingWorker extends Model
{

    public static function GeneratePingFiles()
    {
        $ips = array();
        $array1 = array();
        $array2 = array();
        $array3 = array();
        $array4 = array();
        $array5 = array();
        $array6 = array();
        $array7 = array();
        $array8 = array();
        $array9 = array();
        $array10 = array();
        $array11 = array();
        $array12 = array();
        $array13 = array();
        $array14 = array();
        $array15 = array();
        $array16 = array();
        $array17 = array();
        $array18 = array();
        $array19 = array();
        $array20 = array();
        try {
            $devices = Device::get();
            foreach ($devices as $device) {
                $ips[] = $device->ip;
            }
            $arraycounter = 0;

            foreach ($ips as $ip) {
                if ($arraycounter == "0") {
                    $array1[] = $ip;
                }
                if ($arraycounter == "1") {
                    $array2[] = $ip;
                }
                if ($arraycounter == "2") {
                    $array3[] = $ip;
                }
                if ($arraycounter == "3") {
                    $array4[] = $ip;
                }
                if ($arraycounter == "4") {
                    $array5[] = $ip;
                }
                if ($arraycounter == "5") {
                    $array6[] = $ip;
                }
                if ($arraycounter == "6") {
                    $array7[] = $ip;
                }
                if ($arraycounter == "7") {
                    $array8[] = $ip;
                }
                if ($arraycounter == "8") {
                    $array9[] = $ip;
                }
                if ($arraycounter == "9") {
                    $array10[] = $ip;
                }
                if ($arraycounter == "10") {
                    $array11[] = $ip;
                }
                if ($arraycounter == "11") {
                    $array12[] = $ip;
                }
                if ($arraycounter == "12") {
                    $array13[] = $ip;
                }
                if ($arraycounter == "13") {
                    $array14[] = $ip;
                }
                if ($arraycounter == "14") {
                    $array15[] = $ip;
                }
                if ($arraycounter == "15") {
                    $array16[] = $ip;
                }
                if ($arraycounter == "16") {
                    $array17[] = $ip;
                }
                if ($arraycounter == "17") {
                    $array18[] = $ip;
                }
                if ($arraycounter == "18") {
                    $array19[] = $ip;
                }
                if ($arraycounter == "19") {
                    $array20[] = $ip;
                }


                $arraycounter++;
                if ($arraycounter == 20) {
                    $arraycounter = 0;
                }
            }

            for ($i = 1; $i <= 20; $i++) {
                $filename = config('fping.output') . $i . 'history.txt';
                if (file_exists($filename)) {
                    unlink($filename);
                }
                touch($filename);
                $text = "";
                foreach (${"array" . $i} as $ip) {
                    $text .= $ip . "\n";
                }
                $fh = fopen($filename, "w");
                fwrite($fh, $text) ;
                fclose($fh);
            }

            //$i--;
//        Log::info("$i files created for pings by PingWorker");

            exit;
        }catch(\Exception $e){

        }
    }

    public static function StartPingWorker($worker)
    {
        $results = array();
        try{
        $finalarray = array();
        $startTime = microtime(true);
        $file = config('fping.path') . 'pings/' . $worker . 'history.txt';
        $iplists = file($file);
        $command = config('fping.execpath')." -f $file -c 10 2>&1";
        exec($command, $resultsms);
        foreach ($iplists as $iplist){
            $iplist = preg_replace("/\n/", "", $iplist);
                $finalarray[$iplist] = "-";
        }
        foreach($resultsms as $resultsm){
            if(NULL!=strpos($resultsm,'rcv')){
                $resultsm = preg_replace('/    /',' ',$resultsm);
                $resultsm = preg_replace('/   /',' ',$resultsm);
                $resultsm = preg_replace('/  /',' ',$resultsm);
                $results[] = $resultsm;
            }
        }
        foreach ($results as $key => $result) {
                $matrix[]     = preg_split("/ /", $result);
        }


        foreach ($matrix as $iprow){
            $ip = trim($iprow[0]);

            $loss = preg_split('/\//',$iprow[4]);
            $packet_loss = preg_replace('/\%/','',$loss[2]);
            if($packet_loss =="100"){
                $min = -1;
                $max = -1;
                $avg = -1;
                $jitter = -1;
            }else {
                $responses = preg_split('/\//', $iprow[7]);
                $min = $responses[0];
                $max = $responses[2];
                $avg = $responses[1];
                $jitter = $max - $min;
            }
            $data[] = array(
                "ip" => $ip,
                "min" => $min,
                "avg" => $avg,
                "max" => $max,
                "jitter" =>$jitter,
                "packet_loss"=> $packet_loss
            );
        }

        foreach ($data as $key=> $row){

            $time = time();
            if(!file_exists("/var/www/html/dte/rrd/pings/".trim($row["ip"]).".rrd")){
                echo "NO RRD FOUND \n";
                $options = array(
                    '--step',config('rrd.step'),
                    "--start", "-1 day",
                    "DS:min:GAUGE:900:U:U",
                    "DS:avg:GAUGE:900:U:U",
                    "DS:max:GAUGE:900:U:U",
                    "DS:jitter:GAUGE:900:U:U",
                    "DS:packet_loss:GAUGE:900:U:U",
                    "RRA:".config('rrd.ds').":0.5:1:".config('rrd.rows')
                );

                if(!\rrd_create("/var/www/html/dte/rrd/pings/".trim($row["ip"]).".rrd",$options)){
                    echo rrd_error();
                }
            }else{
                $rrdFile ="/var/www/html/dte/rrd/pings/".trim($row["ip"]).".rrd";
                //\Log::info("Updating RRD for $ip");
                $updator = new \RRDUpdater($rrdFile);
                $updator->update( array(
                    "min" => round($row["min"],2),
                    "avg" => round($row["avg"],2),
                    "max" => round($row["max"],2),
                    "jitter" => round($row["jitter"],2),
                    "packet_loss" => round($row["packet_loss"],2)
                ), $time);
            }
        }
        if (file_exists($file)) {
            unlink($file);
            $rm_command = "rm ".$file;
            exec($rm_command);
        }
        $time = (microtime(true) - $startTime);
//        Log::info("Historical ping process $worker took $time seconds to complete");
        exit;
        }catch (\Exception $e){
            dd($e);
        }
    }

    public function getDevicePings($device,$time){
        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );

        $db = $client->dte;
        $query = "SELECT * FROM pings where host ='".$device->ip."' and time > '".$time ."' order by time desc limit 0";
        $stats = $db->query($query);
        if (isset($stats)) {
            foreach ($stats as $stat) {
                $date = preg_split("/\T/", $stat->time);
                $time = preg_split("/\./", $date['1']);
                $time = preg_split("/\:/",$time['0']);
                $hour = ($time['0']+ 2);
                $minutes = $time['1'];
                $seconds = $time['2'];
                if ($hour < 10){
                    $hour = "0".$hour;
                }
                $time = $hour.":".$minutes;
                $newtime = $date['0'] . " " . $time;
                $stat->time = $newtime;
                $array[] = array(
                    "year" => $stat->time,
                    "value" => $stat->value
                );
            }
        }
        if (isset($array)){
            return $array;
        }else return "NoPingsFound";
    }

}
