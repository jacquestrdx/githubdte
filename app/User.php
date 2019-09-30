<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserNotification;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_type','receive_reports','receive_notifications',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getName($id){
        $user = User::find($id);
        if(isset($user->name)){
            return $user->name;
        }else{
            return "User";
        }
    }

    public function usernotifications()
    {
        return $this->hasMany('App\UserNotification');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function getMessageCount(){
        return $notificationcount = UserNotification::where('interfacewarning_id','!=','0')->where('user_id',$this->id)->count();
    }

    public function getNotificationCount(){
        return $notifications = UserNotification::where('user_id',$this->id)->where('is_read',"0")->count();
    }

    public function getNotifications(){
        return $notifications = UserNotification::where('user_id',$this->id)->where('is_read',"0")->get();
    }
}
