<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class Locationaudit extends Model
{

    public static function createEntry($data){
        $deviceaudit = new Locationaudit();
        $deviceaudit->user_id = $data['user_id'];
        $deviceaudit->location_id = $data['location_id'];
        $deviceaudit->action = $data['action'];
        $deviceaudit->device_ip = $data['device_ip'];
        $deviceaudit->location_name = $data['location_name'];
        $deviceaudit->save();
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
