<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;

class UserNotification extends Model
{
    protected $table = 'usernotifications';

    public function notification()
    {
        return $this->belongsTo('App\Notification');
    }
    public function users(){
        return $this->belongsTo('App\User');
    }

    public function device(){
        return $this->belongsTo('App\Device');
    }

    public function interfacewarning(){
        return $this->belongsTo('App\InterfaceWarning');
    }

    public static function deleteOld(){
        \DB::delete('delete from usernotifications');
    }

    public static function markAllAsRead(){
        $usernotifications = UserNotification::where('user_id',Auth::user()->id)->get();
        foreach ($usernotifications as $usernotification){
            $usernotification->is_read = 1;
            $usernotification->save();
        }
    }


    public function getInterfaceWarnings($id){

    }

    public static function getnotificationsounds($id)
    {
        $usernotifications = UserNotification::where('user_id',$id)->where('interfacewarning_id','!=','0')->get();
        foreach ($usernotifications as $usernotification){
            $usernotification->completed = 1;
            $usernotification->save();
            $array[] = $usernotification;
        }
        if(isset($array)){
            return $array;
        }else return $array[] = "";

    }

    public static function markAsRead($id){
        $usernotifications = UserNotification::where('user_id',Auth::user()->id)->where('id',$id)->where('interfacewarning_id','!=','0')->get();
        foreach ($usernotifications as $usernotification){
            $usernotification->is_read = 1;
            $usernotification->save();
        }
    }
}
