<?php

namespace App;

use App\Location;
use Illuminate\Database\Eloquent\Model;

class Blackboardalerts extends Model
{
    public function device(){
        return $this->belongsTo('App\Device');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }


}
