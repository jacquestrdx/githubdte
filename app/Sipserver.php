<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sipserver extends Model
{
    public function sipaccounts()
    {
        return $this->hasMany('App\Sipaccount');
    }

    public function getOnlineSipAccounts(){
        return Sipaccount::where('status_id','=','1')->where('sipserver_id','=',$this->id)->where('upstreamTrunk','!=',"1")->count();
    }
    public function getOfflineSipAccounts(){
        return Sipaccount::where('status_id','=','2')->where('sipserver_id','=',$this->id)->where('upstreamTrunk','!=',"1")->count();
    }
    public function getAckSipAccounts(){
        return Sipaccount::where('status_id','=','3')->where('sipserver_id','=',$this->id)->where('upstreamTrunk','!=',"1")->count();
    }



}
