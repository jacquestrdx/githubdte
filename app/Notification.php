<?php

namespace App;

use App\Jacques\Mailer;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\UserNotification;

class Notification extends Model
{
    public function device()
    {
        return $this->belongsTo('App\Device');
    }
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function usernotficications()
    {
        return $this->hasMany('App\UserNotification');
    }
//
    public function sendToUsers(){
        $users = User::get();
        foreach ($users as $user){
            $usernotification = new UserNotification();
            $usernotification->user_id = $user->id;
            $usernotification->device_id = $this->device_id;
            $usernotification->notification_id = $this->id;
            $usernotification->interfacewarning_id = 0;
            $usernotification->completed = 0;
            $usernotification->save();
        }
    }

    public static function sendHourlyEmailToUsers($interval){
        if ($interval =="0"){}else{
        $notificationarray = array();
        $timestr = " - ".$interval." hour";
        $timestamp = strtotime($timestr);
        $date = date_create();
        $message = '<html><head><style>table { border-collapse: collapse;    border-style: hidden;} table td, table th {border: 1px solid black;}</style></head><body>';
        $message.=" </br>";
        date_timestamp_set($date, $timestamp);
        $formatted_time = date_format($date, 'Y-m-d H:i:s');
        $notifications = Notification::where('created_at','>=',$formatted_time)->where('client_id',"0")->orderby('created_at','ASC')->get();
        $users = User::get();
        foreach ($notifications as $notification){
            $notificationarray[$notification->device->getLocationName()][$notification->device->name][] = $notification;
        }


        $message .= "</br> <b>The follwing interfaces changed state</b></br><ul>";
        $message .= "<table style='border: solid;border-width: 2px'> <thead><tr><th>Device</th><th>IP</th><th>Status</th><th>Time</th></tr></thead><tbody>";
        $backhaulInterfaces = \DB::SELECT('select dinterface_id from backhauls');
        foreach ($backhaulInterfaces as $backhaulInterface){
            $backhaul_ids[] = $backhaulInterface->dinterface_id;
        }
        $interfacelogs = Interfacelog::with('device')->whereIn('dinterface_id',$backhaul_ids)->where('created_at','>',$formatted_time)->orderby('created_at','DESC')->get();
        foreach ($interfacelogs as $interfacelog){
                $message .= "<tr><td>".$interfacelog->device->name."</td><td>".$interfacelog->device->ip."</td> <td>".$interfacelog->status."</td><td> ".
                $interfacelog->created_at->format('Y-m-d H:i:s')."</td></tr>";
        }
            $message .="</tbody></table></br></br></br>";

        $subject = "Hourly DTE report ".config('url.root_url');
        $instancebackhaul = Backhaul::first();
        $backhauls = \DB::SELECT('select locations.name as locationname,backhauls.to_location_id,interfaces.txspeed,interfaces.rxspeed,interfaces.maxtxspeed,interfaces.maxrxspeed,backhaultypes.name,interfaces.threshhold 
        from backhauls  
        inner join interfaces on interfaces.id = backhauls.dinterface_id
        inner join locations on backhauls.location_id = locations.id 
        inner join backhaultypes on backhaultypes.id = backhauls.backhaultype_id 
        order by interfaces.rxspeed DESC limit 20');

        $message .= "</br> <b> The following happened on backhauls  since $formatted_time</b>";
        $message .= "<table style='border: solid;border-width: 2px'> <thead><tr><th>Highsite</th><th>Backhaul</th><th>TX</th><th>RX</th></tr></thead><tbody>";
        foreach ($backhauls as $backhaul){
            $message.= " <tr><td> $backhaul->locationname </td><td> ".$instancebackhaul->getTo_location($backhaul->to_location_id)." </td><td>". $backhaul->txspeed. "Mbps </td><td> ".$backhaul->rxspeed." Mbps</td></tr>";
        }
        $message .="</tbody></table></br></br></br>";
            $message .= "</br> <b> The following OUTAGES happened since $formatted_time</b>";

//    foreach($notificationarray as $thekey => $arrays) {
//        $message .= "</br><b>" . $thekey . "</b> </br><ul>";
//        foreach ($arrays as $key => $array) {
//            $message .= "</br><li><b>" . $key . "</b> </br></li>";
//            foreach ($array as $item) {
//                if ($item->type == "sound") {
//                    $message .= "<li>" . "Down at " . date_format($item->created_at, 'y-m-d H:i:s') . "</li>";
//                } else {
//                    $message .= "<li>" . "Up at " . date_format($item->created_at, 'y-m-d H:i:s') . "</li>";
//                }
//            }
//        }
//        $message .= "</ul>";
//    }
            $message.= "</br> </br></br><table style='border: solid;border-width: 2px'> <thead><tr><th>Highsite</th><th>Device</th><th>Event Type</th><th>Time</th></tr></thead><tbody>";
            foreach($notificationarray as $thekey => $arrays) {
                $message.="<tr style='height: 20px'><td colspan='4'><b>$thekey</b></td></tr>";
                foreach ($arrays as $key => $array) {
                    foreach ($array as $item) {
                        if ($item->type == "sound") {
                            $message .= "<tr><td>" . $thekey . "</td>";
                            $message .= "<td>" . $key . "</td>";
                            $message .= "<td> Down </td><td>" . date_format($item->created_at, 'y-m-d H:i:s') . "</td></tr>";
                        } else {
                            $message .= "<tr><td>" . $thekey . "</td>";
                            $message .= "<td>" . $key . "</td>";
                            $message .= "<td> Up </td><td>" . date_format($item->created_at, 'y-m-d H:i:s') . "</td></tr>";
                        }
                    }
                }
            }
            $message.= "</tbody></table></body>";


//            foreach($notificationarray as $thekey => $arrays) {
//                $message .= "</br><b>" . $thekey . "</b> </br><ul>";
//                foreach ($arrays as $key => $array) {
//                    $message .= "</br><li>" . $key ." had ".sizeof($array)." up and down events between ".$formatted_time." and now </li>";
//                }
//                $message .= "</ul>";
//            }

        foreach ($users as $user){
            if($user->receive_reports =="1"){
                Mailer::sendMail($message,$subject,$user);
            }
        }
        }
    }

    public static function calculateDowntime($device,$timestamp){
        $date = date_create();
        date_timestamp_set($date, $timestamp);
        $formatted_time = date_format($date, 'Y-m-d H:i:s');
        $notificationsobj = Notification::where('device_id',$device->id)->where('created_at','>=',$formatted_time)->orderBy('created_at','ASC')->get();
        $downtime = 0;
        $count = 0;
        foreach($notificationsobj as $object)
        {
            $notifications[] = $object->toArray();
        }

        if(!isset($notifications)){
            return 0;
        }
        foreach ($notifications as $key => $notification){
            if ($count ==0){
                if ($notification['type'] == "log"){
                    $downtime = strtotime($notification['created_at']) - $timestamp;
                   // echo $count." ".$downtime."\n";
                }else{
                    //$downtime = strtotime($notification['created_at']) - $timestamp ;
                    //echo $count." ".$downtime."\n";
                }
            }

            if($count == sizeof($notifications)) {
                if ($notification['type'] == 'sound'){
                    $downtime +=  (time() - strtotime($notification['created_at']));
                    //echo $count." ".$downtime."\n";
                }
            }

            if ($count != 0){
                if (($notification['type'] == "log") and ($notifications[$count-1]['type']=="sound") ){
                    $downtime += strtotime($notification['created_at'])-strtotime($notifications[$count-1]['created_at']);
                    //echo $count." ".$downtime."\n";
                }else{
                }
            }
            $count++;
        }

        return $downtime;
    }

    public static function calculateWeeklyDowntime($device){
        $timestamp = strtotime('Monday this week');
       return Notification::calculateDowntime($device,$timestamp);
    }
    //
    public static function calculateMonthlyDowntime($device){
        $timestamp = strtotime('first day of this Month midnight');
        return Notification::calculateDowntime($device,$timestamp);
    }
    public static function calculateDailyDowntime($device){
        $timestamp = strtotime('today midnight');
        return Notification::calculateDowntime($device,$timestamp);
    }

    public static function calculate7daysDowntime($device){
        $timestamp = strtotime('-7 days');
        return  Notification::calculateDowntime($device,$timestamp);
    }
    public static function calculate24hDowntime($device){
        $timestamp = strtotime('-24h');
        return Notification::calculateDowntime($device,$timestamp);
    }
    public static function calculate30daysDowntime($device){
        $timestamp = strtotime('-30 days');
        return Notification::calculateDowntime($device,$timestamp);
    }

}
