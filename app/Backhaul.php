<?php

namespace App;

use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use App\Neighbor;
use App\Possible_backhaul;
use Illuminate\Database\Eloquent\Model;

class Backhaul extends Model
{
    protected $fillable = ['id','location_id','dinterface_id','backhaultype_id','to_location_id','priority','description'];

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function backhaultype(){
        return $this->belongsTo('App\Backhaultype');
    }

    public function getTo_location($id){
        $location =Location::find($id);
        return $location->name;
    }
    public function getTo_locationName(){
        $location = Location::find($this->to_location_id);
        return $location->name;
    }

    public function dinterface(){
        return $this->belongsTo('App\DInterface');
    }

    public function getFromSiteLat(){
        return $this->location->lat;
    }

    public function getFromSiteLong(){
        return $this->location->lng;
    }

    public function getToSiteLong(){
        $location = Location::find($this->to_location_id);
        return $location->lng;
    }

    public function getToSiteLat(){
        $location = Location::find($this->to_location_id);
        return $location->lat;
    }
    public static function findPossibleBackhauls(){
        $dinterfaces = DInterface::get();

        foreach ($dinterfaces as $dinterface){
            if(Neighbor::where('mac_address',$dinterface->mac_address)->exists()){
                $neighbor = Neighbor::where('mac_address',$dinterface->mac_address)->first();
                if(!Possible_backhaul::where('from_device_id',$neighbor->device_id)->where('to_device_id',$dinterface->device_id)->exists())
                {
                    $possible_backhaul = new Possible_backhaul();
                    $possible_backhaul->from_device_id = $neighbor->device_id;
                    $possible_backhaul->from_location = $neighbor->device->location_id;
                    $possible_backhaul->to_location = $dinterface->device->location_id;
                    $possible_backhaul->to_device_id = $dinterface->device_id;
                    $possible_backhaul->save();
                }

            }
        }

    }

    public static function updateBackhaulInterfaces($job){
        $mikrotiklibrary = new MikrotikLibrary();
        $backhauls = Backhaul::with('dinterface')->get();
        if($backhauls->count() < 5){
            $count = 5;
        }else{
            $count = ($backhauls->count()/5);
        }
        $chunks = $backhauls->chunk($count);
        foreach ($chunks[$job] as $backhaul) {
                try {
                    $device_ids[] = $backhaul->dinterface->device->id;
                } catch (\Exception $e) {
                };
            $devices = Device::whereIn('id', $device_ids)->get();
            foreach ($devices as $device) {
                $mikrotiklibrary->getOneBackhaulInterface($device);
            }
        }

    }

    public static function fixAllBackhauls(){
        $backhauls = Backhaul::get();
        foreach ($backhauls as $backhaul){
            if (isset($backhaul->dinterface)){
                if($backhaul->dinterface->maxtxspeed =="0"){
                    $backhaul->dinterface->maxtxspeed = $backhaul->dinterface->txspeed;
                    $backhaul->dinterface->save();
                }
                if($backhaul->dinterface->maxrxspeed =="0"){
                    $backhaul->dinterface->maxrxspeed = $backhaul->dinterface->rxspeed;
                    $backhaul->dinterface->save();
                }

                if($backhaul->dinterface->maxtxspeed > ($backhaul->dinterface->txspeed*4) ){
                    $backhaul->dinterface->maxtxspeed = $backhaul->dinterface->txspeed;
                    $backhaul->dinterface->save();
                }
                if($backhaul->dinterface->maxrxspeed > ($backhaul->dinterface->rxspeed*4)){
                    $backhaul->dinterface->maxrxspeed = $backhaul->dinterface->rxspeed;
                    $backhaul->dinterface->save();
                }
            }
        }
    }




}
