<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Acknowledgement;
use Illuminate\Support\Facades\Log;
use App\UserNotification;

class ClientPingWorker extends Model
{

    public static function GeneratePingFiles()
    {
        $clients = Client::get();

        //Log::info(count($clients) . " clients ready to be pinged by PingWorker");
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

            $arraycounter++;
            if ($arraycounter == 10) {
                $arraycounter = 0;
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $filename = config('fping.output') ."client".$i . '.txt';
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
        exit;


        //$i--;
        //Log::info("$i files created for pings by PingWorker");

    }

    public static function StartPingWorker($worker)
    {
        $debug = true;
        $startTime = microtime(true);

        $file = config('fping.path') . 'pings/client' . $worker . '.txt';

        $command = config('fping.execpath')." < $file 2> /dev/null";

        exec($command, $results);
        foreach ($results as $result) {

            if (preg_match("/alive/", $result)) {
                $IP     = preg_split("/ /", $result);
                $client = Client::where('ip', '=', $IP['0'])->first();
                if(isset($client)) {
                    if ($debug == true) {
                        Log::info("$client->name $client->ping $client->ping1 $client->ping2 $client->ping3");
                    }
                        $client->ping3 = $client->ping2;
                        $client->ping2 = $client->ping1;
                        $client->ping1 = "1";
                        if ($debug == true) {
                            Log::info("Ping process $worker pinged $client->name as ping1: $client->ping1");
                        }
                        $client->lastseen = date("Y-m-d h:i:sa");
                        ClientPingWorker::setUpDown($client, $worker);
                        $client->save();
                        if ($debug == true) {
                            Log::info("$client->name JUST SAVED at " . date("Y-m-d h:i:sa") . " IF ALIVE YES");
                            Log::info("JUST SAVED $client->name $client->ping $client->ping1 $client->ping2 $client->ping3");
                        }
                    }

            }else {
                $IP = preg_split("/ /", $result);
                $client = Client::where('ip', '=', $IP['0'])->first();
                if (isset($client)) {
                    if ($debug == true) {
                        Log::info("$client->name $client->ping $client->ping1 $client->ping2 $client->ping3");
                    }
                    $client->ping3 = $client->ping2;
                    $client->ping2 = $client->ping1;
                    $client->ping1 = "0";
                    if ($debug == true) {
                        Log::info("Ping process $worker pinged $client->name as ping1: $client->ping1");
                    }
                    ClientPingWorker::setUpDown($client, $worker);
                    $client->save();
                    if ($debug == true) {
                        Log::info("$client->name JUST SAVED at " . date("Y-m-d h:i:sa") . " IF ALIVE NO");
                        Log::info(" JUST SAVED $client->name $client->ping $client->ping1 $client->ping2 $client->ping3");
                    }

                }
            }
        }

        if (file_exists($file)) {
            unlink($file);
            $rm_command = "rm ".$file;
            exec($rm_command);
        }
        $time = (microtime(true) - $startTime);

        if ($debug == true){
            Log::info("Ping process $worker took $time seconds to complete");
        }

    }

    public static function setUpDown($client,$worker)
    {

        $debug = false;
        if (
            ($client->ping1 == "1" and $client->ping2 == "1")
            or
            ($client->ping2 == "1" and $client->ping3 == "1")
            or
            ($client->ping1 == "1" and $client->ping3 == "1")
        ){
            if ($client->ping == "0") {
                $notification            = new Notification;
                $notification->client_id = $client->id;
                $notification->device_id = "0";
                echo "$client->name ping is now up";
                $notification->message   = "$client->name ping is now up";
                $notification->done      = "0";
                $notification->type      = "log";
                $notification->save();
                $client->ping         = "1";
                if ($debug==true){
                    Log::info("$client->name , $client->ping1 , $client->ping2 , $client->ping3 came back online!!");
                }
                $client->save();

            }
        }else {
            if ($client->ping == "1") {
                $notification            = new Notification;
                $notification->client_id = $client->id;
                $notification->device_id = "0";
                $notification->message   = "$client->name ping is now down";
                echo "$client->name ping is now up";
                $notification->done      = "0";
                $notification->type      = "sound";
                $notification->save();
                $client->lastdown = new \DateTime();
                $client->downs_today = $client->downs_today + 1;
                $client->ping = "0";
                if ($debug==true) {
                    Log::info("$client->name , $client->ping1 , $client->ping2 , $client->ping3 went offline!!");
                }
                $client->save();
            }
        }
    }

}
