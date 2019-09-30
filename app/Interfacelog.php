<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class Interfacelog extends Model
{

    public function device(){
        return $this->belongsTo('App\Device');
    }
    public function dinterface(){
        return $this->belongsTo('App\DInterface');
    }

    public function readableStatus(){
        $stringstatus = $this->status;
        if(NULL!=strpos($stringstatus,'10000000000')){
            $stringstatus = preg_replace('/1000000000/','10000',$stringstatus);
        }
        if(NULL!=strpos($stringstatus,'1000000000')){
            $stringstatus = preg_replace('/1000000000/','1000',$stringstatus);
        }
        if(NULL!=strpos($stringstatus,'100000000')){
            $stringstatus = preg_replace('/100000000/','100',$stringstatus);
        }

        return $stringstatus;
    }



}
