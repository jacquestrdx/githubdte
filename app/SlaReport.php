<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;
use App\Jacques\Mailer;
use App\Jacques\DeviceNameFinder;
class SlaReport extends Model
{
    public function device()
    {
        return $this->belongsTo('App\Device');
    }




    public static function generateDeviceMonthICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('first day of this Month midnight');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();
        foreach ($devices as $device) {
        $secondsdowntime = Notification::calculateMonthlyDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
        $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
                $finalresults[] = [
                    "device" => $device->name,
                    "ip" => $device->ip,
                    "total-downtime" => $secondsdowntime,
                    "uptime" => $uptime
                ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/devicemonthreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }


    public static function generateDeviceDayICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('today midnight');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();
        foreach ($devices as $device) {
            $secondsdowntime = Notification::calculateDailyDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }

            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/devicedayreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }

    public static function generateDevice7daysICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('-7 days');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();
        foreach ($devices as $device) {
            $secondsdowntime = Notification::calculate7daysDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/device7daysreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }

    public static function generateDevice24hICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('-24 hours');

        $currentdate = date_create();

        $time = $currentdate->format('U') - $reportstartdate->format('U');

        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();

        foreach ($devices as $device) {
            $secondsdowntime = Notification::calculate24hDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/device24hreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }

    public static function generateDevice30daysICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('-30 days');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();
        foreach ($devices as $device) {
            echo "\n ".$device->name;
            $secondsdowntime = Notification::calculate30daysDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
            echo " ".$uptime." % \n";
        }

        $file = fopen("/var/www/html/dte/storage/reports/device30daysreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }



    public static function generateDeviceWeekICMPReport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('Monday this week midnight');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->where('created_at','<',$formatted_time)->with('notifications')->get();

        foreach ($devices as $device) {
            $secondsdowntime = Notification::calculateWeeklyDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/deviceweekreport.csv","w");

        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
        }
        fclose($file);
        return $finalresults;
    }

    public static function sendDailyreport()
    {
        $reportstartdate = date_create();
        $reportstartdate->modify('today midnight');
        $currentdate = date_create();
        $time = $currentdate->format('U') - $reportstartdate->format('U');
        $formatted_time =  $reportstartdate->format('Y-m-d H:i:s');
        $devices = Device::with('location')->with('notifications')->get();

        foreach ($devices as $device) {
            $secondsdowntime = Notification::calculateWeeklyDowntime($device);
            if ($secondsdowntime == "0"){
                if ($device->ping == "0"){
                    $secondsdowntime=$time;
                }else{
                    $secondsdowntime=0;
                }
            }
            $uptime =  round((100 - ($secondsdowntime/$time)*100),2);
            $finalresults[] = [
                "device" => $device->name,
                "ip" => $device->ip,
                "total-downtime" => $secondsdowntime,
                "uptime" => $uptime
            ];
        }
        $file = fopen("/var/www/html/dte/storage/reports/dailyemailreport.csv","w");
        $message = "Uptimes this week \n \r";
        $subject = "Uptime report for $reportstartdate->format('Y-m-d H:i:s');";
        foreach ($finalresults as $line)
        {
            fputcsv($file,$line);
            $message .= $line."\n \r";
        }
        fclose($file);

        Mailer::sendMail($message,$subject);
        return $finalresults;


    }



}
