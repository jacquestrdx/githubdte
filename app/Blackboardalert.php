<?php

namespace App;

use App\Location;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Device;

class Blackboardalert extends Model
{
    protected $fillable = ['acknowledged'];
    public function device(){
        return $this->belongsTo('App\Device');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }
    


    public function getAckUser()
    {
        $acknowledgement = Acknowledgement::where('blackboard_id',$this->id)->orderby('created_at','DESC')->first();
        if (count($acknowledgement)){
            $user_id = $acknowledgement->user_id;
        }else $user_id = "";

        $user = User::where('id', $user_id)->first();
        if (count($user)){
            return $user->name;
        }else return "";
    }

    public function getAckID()
    {
        $acknowledgement = Acknowledgement::where('blackboard_id',$this->id)->orderby('created_at','DESC')->first();
        if (count($acknowledgement)){
            $id = $acknowledgement->id;
        }else $id = "";

        return $id;
    }

    public function getAcknowledgementNote(){
        $acknowledgement = Acknowledgement::where('blackboard_id',$this->id)->orderby('created_at','DESC')->first();
        if (count($acknowledgement)){
            return $acknowledgement->ack_note;
        }else return "";
    }


}
