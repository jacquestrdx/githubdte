<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\UserNotification;

class InterfaceWarning extends Model
{
    protected $table = 'interface_warnings';

    public function dinterface()
    {
        return $this->belongsTo('App\DInterface');
    }

    public function device()
    {
       $device = $this->dinterface->device;
       return $device->name;
    }

    public static function pushAll(){
        $users = User::get();
        $interfacewarnings = InterfaceWarning::get();
        foreach ($interfacewarnings as $interfacewarning) {
            try{
            foreach ($users as $user) {
                if(!UserNotification::where('user_id',$user->id)->where('interfacewarning_id',$interfacewarning->id)->exists()){
                $usernotification = new UserNotification();
                $usernotification->user_id = $user->id;
                echo "$interfacewarning->message sent to $user->name \n";
                $deviceid = $interfacewarning->dinterface->device_id;
                $usernotification->device_id = $deviceid;
                $usernotification->notification_id = 0;
                $usernotification->interfacewarning_id = $interfacewarning->id;
                $usernotification->completed = 0;
                $usernotification->save();
                }else{
                    $usernotification =  UserNotification::where('user_id',$user->id)->where('interfacewarning_id',$interfacewarning->id)->first();
                    $usernotification->user_id = $user->id;
                    $deviceid = $interfacewarning->dinterface->device_id;
                    $usernotification->device_id = $deviceid;
                    echo "$interfacewarning->message updated on $user->name \n";
                    $usernotification->notification_id = 0;
                    $usernotification->interfacewarning_id = $interfacewarning->id;
                    $usernotification->completed = 0;
                    $usernotification->save();
                }
            }
            }catch (\Exception $e){

            }
        }
    }
}
