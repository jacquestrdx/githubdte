<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;

class Neighbor extends Model
{
    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public static function verifyNeighbors(){
        $neighbors = Neighbor::get();

        foreach ($neighbors as $neighbor){
            if(Device::where('ip',$neighbor->ip)->exists()){
                $neighbor->verified = 1;
                echo "$neighbor->mac_address is verified \n";
                $neighbor->save();
            }else{
                $neighbor->verified = 0;
                echo "$neighbor->mac_address is NOT verified \n";
                $neighbor->save();
            }
        }
    }

}
