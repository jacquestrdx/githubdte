<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class Antenna extends Model
{
    protected $fillable = ['id','gain','vertical','horizontal','created_at','updated_at','description'];

    public function device(){
        return $this->hasMany('App\Device');
    }



}
