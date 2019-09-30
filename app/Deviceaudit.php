<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class Deviceaudit extends Model
{

    public static function createEntry($data){
        $deviceaudit = new Deviceaudit();

        $deviceaudit->user_id = $data['user_id'];
        $deviceaudit->device_id = $data['device_id'];
        $deviceaudit->action = $data['action'];
        $deviceaudit->device_ip = $data['device_ip'];
        $deviceaudit->device_name = $data['device_name'];

        $deviceaudit->save();
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
