<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BGPPeer extends Model
{
    protected $fillable = ['ack_note', 'acknowledged','ack_user_id'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function interfaces(){
        return $this->hasManu('App\Deviceinterface');
    }


    public function acknowledgements()
    {
        return $this->hasMany('App\Acknowledgement');
    }

    public function getUser($id){
        $user = User::where('id','=',$id)->first();
         return $user->name;
    }

    public function getAckUser()
    {
        $acknowledgement = Acknowledgement::where('bgppeer_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            $user_id = $acknowledgement->user_id;
        }else $user_id = "";

        $user = User::where('id', $user_id)->first();
        if (count($user)){
            return $user->name;
        }else return "";
    }

    public function getAcknowledgementNote(){
        $acknowledgement = Acknowledgement::where('bgppeer_id',$this->id)->where('active',"1")->first();
        if (count($acknowledgement)){
            return $acknowledgement->ack_note;
        }else return "";
    }


}
