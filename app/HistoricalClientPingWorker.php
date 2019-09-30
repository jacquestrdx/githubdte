<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Acknowledgement;
use Illuminate\Support\Facades\Log;

class HistoricalClientPingWorker extends Model
{

    public static function GeneratePingFiles()
    {
        $clients = Client::get();

//        Log::info(count($clients) . " clients ready to be pinged by HistoricalPingWorker");
        foreach ($clients as $client) {
            $ips[] = $client->ip;
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
            $filename = config('fping.output') . $i . 'clienthistory.txt';
            if (file_exists($filename)) {
                unlink($filename);
            }
            touch($filename);
            $text = "";
            foreach (${"array".$i} as $ip) {
                $text .= $ip . "\n";
            }
            $fh = fopen($filename, "w") or die("Could not open file $i.");
            fwrite($fh, $text) or die("Could not write file $i!");
            fclose($fh);
        }

        //$i--;
//        Log::info("$i files created for pings by PingWorker");

        exit;

    }

    public static function StartPingWorker($worker)
    {
        $finalarray = array();
        $startTime = microtime(true);
        $file = config('fping.path') . 'pings/' . $worker . 'clienthistory.txt';
        $iplists = file($file);
        $command = config('fping.execpath')." -f $file -c 1";
        exec($command, $resultsms);
        foreach ($iplists as $iplist){
            $iplist = preg_replace("/\n/", "", $iplist);
                $finalarray[$iplist] = "-";
        }
        foreach ($resultsms as $result) {
                $IPArray     = preg_split("/:/", $result);
                $ip = trim($IPArray['0']);
                $Response_Time_Array = preg_split('/bytes\,/',$IPArray['1']);
                $Response_Time_Array2 = preg_split('/\(/',$Response_Time_Array['1']);
                $Response_Time_Array3 = preg_split('/ms/',$Response_Time_Array2['0']);
                $response_time = trim($Response_Time_Array3['0']);
                $finalarray[$ip] = $response_time;
        }




        foreach ($finalarray as $ip => $response_time){
           if ($response_time == "-"){
               $response_time = -1;
           }

            $time = time();
            $command = "curl -i -XPOST 'http://localhost:8086/write?db=dte&precision=s' --data-binary 'clientpings,host=".$ip." value=".$response_time." ".$time."'";
            echo $command."\n";
            exec($command, $ok);
        }
        if (file_exists($file)) {
            unlink($file);
            $rm_command = "rm ".$file;
            exec($rm_command);
        }
        $time = (microtime(true) - $startTime);
//        Log::info("Historical ping process $worker took $time seconds to complete");
        exit;

    }

    public function getClientPings($client,$time){
        $influxclient = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );

        $db = $influxclient->dte;
        $query = "SELECT * FROM clientpings where host ='".$client->ip."' and time > '".$time ."' order by time desc limit 0";
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
