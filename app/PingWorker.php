<?php

namespace App;

use App\Jacques\Mailer;
use Illuminate\Database\Eloquent\Model;
use App\Acknowledgement;
use Illuminate\Support\Facades\Log;
use App\UserNotification;

class PingWorker extends Model
{

    public static function GeneratePingFiles()
    {
        $devices = Device::get();
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
        //Log::info(count($devices) . " devices ready to be pinged by PingWorker");
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
            $filename = config('fping.output') . $i . '.txt';
            if (file_exists($filename)) {
                unlink($filename);
            }
            touch($filename);
            $text = "";
            foreach (${"array".$i} as $ip) {
                $text .= $ip . "\n";
            }
            $fh = fopen($filename, "w");
            fwrite($fh, $text);
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

        $file = config('fping.path') . 'pings/' . $worker . '.txt';

        $command = config('fping.execpath')." -t 250 < $file 2> /dev/null";

        exec($command, $results);
        foreach ($results as $result) {
            if (preg_match("/alive/", $result)) {
                $IP     = preg_split("/ /", $result);
                $device = Device::where('ip', '=', $IP['0'])->first();
                $device->ping4    = $device->ping3 ?? $device->ping4 = "0";
                $device->ping3    = $device->ping2 ?? $device->ping3 = "0";
                $device->ping2    = $device->ping1 ?? $device->ping2 = "0";
                $device->ping1    = "1";
                $device->lastseen = date("Y-m-d h:i:sa");
                PingWorker::setUpDown($device);
                $device->save();
            } else {
                $IP            = preg_split("/ /", $result);
                $device        = Device::where('ip', '=', $IP['0'])->first();
                $device->ping4    = $device->ping3 ?? $device->ping4 = "0";
                $device->ping3 = $device->ping2 ?? $device->ping3 = "0";
                $device->ping2 = $device->ping1 ?? $device->ping2 = "0";
                $device->ping1 = "0";
                PingWorker::setUpDown($device);
                $device->save();
            }
    }

        if (file_exists($file)) {
            unlink($file);
            $rm_command = "rm ".$file;
            exec($rm_command);
        }
        $time = (microtime(true) - $startTime);

        if ($debug == true){
            Log::info("Ping process $worker took $time seconds to complete, it pinged ".sizeof($results)." ips");
        }
        exit;

    }

    public static function setUpDown($device)
    {
        $pingpercentage = 0;
        $debug = true;

        if($device->ping1 =="1"){
            $pingpercentage += 1;
        }
        if($device->ping2 =="1"){
            $pingpercentage += 1;
        }
        if($device->ping3 =="1"){
            $pingpercentage += 1;
        }
        if($device->ping4 =="1"){
            $pingpercentage += 1;
        }

        if (
            $pingpercentage >= 2
        ){
            if ($device->ping == "0") {
                $notification            = new Notification;
                $notification->device_id = $device->id;
                $notification->client_id = "0";
                $epoch = time();
                $notification->epoch = $epoch +(60*60*2);
                $notification->message   = "$device->name ping is now up";
                $notification->done      = "0";
                $notification->type      = "log";
                $notification->save();
                $notification->sendToUsers();
                $usernotifications = UserNotification::where('device_id', $device->id)->get();
                        foreach ($usernotifications as $usernotification) {
                            $usernotification->delete();
                        }

            $device->ping         = "1";
            if ($debug==true){
                Log::info("$device->name , $device->ping1 , $device->ping2 , $device->ping3 came back online!!");
            }
            $device->save();

            if ($device->acknowledged == "1"){
                $device->acknowledged = "0";
                $acknowledgement = Acknowledgement::where('device_id',$device->id)->where('active',"1")->first();
                $acknowledgement->active = "0";
                $acknowledgement->save();
                }
            if ($device->location->acknowledged == "1"){
                    $device->location->acknowledged = "0";
                    $device->location->save();
                    $acknowledgement = Acknowledgement::where('location_id',$device->location_id)->where('active',"1")->first();
                    $acknowledgement->active = "0";
                    $acknowledgement->save();
                }
            }
        }else {
            if ($device->ping == "1") {
                $notification            = new Notification;
                $notification->device_id = $device->id;
                $notification->client_id = "0";
                $notification->message   = "$device->name ping is now down";
                $notification->done      = "0";
                $epoch = time() + (60*60*2);
                $notification->epoch = ($epoch * -1);
                $notification->type      = "sound";
                $notification->save();
                $notification->sendToUsers();
                $device->lastdown = new \DateTime();
                $device->downs_today = $device->downs_today + 1;
                $device->ping = "0";
                if ($debug==true) {
                    Log::info("$device->name , $device->ping1 , $device->ping2 , $device->ping3 went offline!!");
                }
                $device->save();

            }
        }
    }

}
