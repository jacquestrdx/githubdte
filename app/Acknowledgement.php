<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acknowledgement extends Model
{
    public function device(){
        return $this->belongsTo('App\Device');
    }
    public function bgppeer(){
        return $this->belongsTo('App\BGPPeer');
    }

}
