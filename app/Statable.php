<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Fault;

class Statable extends Model
{
    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function stationspec(){
        return $this->hasMany('App\Stationspec');

    }

    public function pppoeclients()
    {
        return $this->hasMany('App\PppoeClient');
    }

    public static function getMac($id){
        $statable = Statable::find($id);
        return $statable->mac;
    }

    public static function checkStationsIntergity()
    {
        $results = array();

        $stations = Statable::get();

        foreach ($stations as $station) {
            if($station->status == 0){
                $deleted = \DB::delete('delete from stationspecs where stationspecs.statable_id ='.$station->id);
            }
            $station->status = 0;
            $station->save();
            $location = $station->device->location;
            $noisefloor = $location->noise_floor;
            $antenna = $station->device->antenna;
            $sectorgain = $antenna->gain;
            $sectoroutput = $station->device->txpower;
            if ($station->device->txpower < 20) {
                $station->device->txpower = 17;
            }
            $cpegain = $station->getCpeGain($station);
            if ($station->distance == 0) {
                $results = array(
                    "message" => "Distance is equal to 0 please check polling",
                    "type" => "Polling",
                    "station_id" => $station->id
                );
                $staspec = new Stationspec();
                $staspec->statable_id = $station->id;
                $staspec->message = $results['message'];
                $staspec->type = $results['type'];
                $staspec->save();


                $station->status = 3;
                $station->save();
            } else {
                $distance = $station->distance;
                if ($station->signal == 0) {
                    $results = array(
                        "message" => "Signal is equal to 0 please check polling",
                        "type" => "Polling",
                        "station_id" => $station->id
                    );
                    if(!Stationspec::where('statable_id', '=', $station->id)->where('message',$results['message'])->exists()){
                        $staspec = new Stationspec();
                        $staspec->statable_id = $station->id;
                        $staspec->message = $results['message'];
                        $staspec->type = $results['type'];
                        $staspec->save();
                    }
                    $station->status = 3;
                    $station->save();
                } else {

                    $freq = $station->device->freq;
                    $gain = $sectoroutput + $sectorgain + $cpegain;
                    $calcdistance = ((20) * LOG($distance, 10));
                    $calcfreq = (20 * log($freq / 1000, 10));
                    $gaindropoff = 6;

                    $desiredsignal = round(($gain - ($calcdistance + $calcfreq + 92.45)) - 4, 2);
                    $maxsignal = $desiredsignal - $gaindropoff;
                    $snr = $noisefloor - $desiredsignal;
                    $maxsnr = $noisefloor - $maxsignal;
                    $workabledistance = round(pow(10, ((($sectorgain + $sectoroutput + $cpegain - 92.45 - 4 - 20 * LOG(($freq / 1000), 10) - ($noisefloor + 30)) / 20))), 2);
                    $maxdistance = round(pow(10, ((($sectorgain + $sectoroutput + $cpegain - 92.45 - 4 - 20 * LOG($freq / 1000, 10) - ($noisefloor + 25)) / 20))), 2);

                    if ($station->signal > -50){
                        if ($station->distance > $maxdistance) {
                            if (is_infinite($maxdistance)) {

                            } else {
                                if ($station->name != "N/A") {
                                    $results = array(
                                        "message" => "$station->distance is more than max $maxdistance",
                                        "type" => "Distance",
                                        "station_id" => $station->id
                                    );
                                    if(!Stationspec::where('statable_id', '=', $station->id)->where('message',$results['message'])->exists()){
                                        $staspec = new Stationspec();
                                        $staspec->statable_id = $station->id;
                                        $staspec->message = $results['message'];
                                        $staspec->type = $results['type'];
                                        $staspec->save();
                                    }
                                    $station->status = 3;
                                    $station->save();
                                }
                            }
                        }
                        if ($station->signal > -35) {
                            if (($station->signal < ($maxsignal - 3))) {
                                if (is_infinite($maxsignal)) {

                                } else {
                                    if ($station->name != "N/A") {
                                        $results = array(
                                            "message" => "$station->signal is more than max $maxsignal",
                                            "type" => "Signal",
                                            "station_id" => $station->id
                                        );
                                        if(!Stationspec::where('statable_id', '=', $station->id)->where('message',$results['message'])->exists()){
                                            $staspec = new Stationspec();
                                            $staspec->statable_id = $station->id;
                                            $staspec->message = $results['message'];
                                            $staspec->type = $results['type'];
                                            $staspec->save();

                                        }
                                        $station->status = 3;
                                        $station->save();

                                    } else {

                                    }

                                }
                            }
                        } else {
                            if (($station->signal < $maxsignal)) {
                                if (is_infinite($maxsignal)) {

                                } else {
                                    if ($station->name != "N/A") {
                                        $results = array(
                                            "message" => "$station->signal is more than max $maxsignal",
                                            "type" => "Signal",
                                            "station_id" => $station->id
                                        );
                                        if (!Stationspec::where('statable_id', '=', $station->id)->where('message', $results['message'])->exists()) {
                                            $staspec = new Stationspec();
                                            $staspec->statable_id = $station->id;
                                            $staspec->message = $results['message'];
                                            $staspec->type = $results['type'];
                                            $staspec->save();
                                        }
                                        $station->status = 3;
                                        $station->save();

                                    } else {

                                    }

                                }
                            } else {
                                if ($station->signal < ($maxsignal + 3)) {
                                    if (is_infinite($maxsignal)) {
                                    } else {
                                        $results = array(
                                            "message" => "$station->signal close to $maxsignal",
                                            "type" => "Signal",
                                            "station_id" => $station->id
                                        );
                                        if (!Stationspec::where('statable_id', '=', $station->id)->where('message', $results['message'])->exists()) {
                                            $staspec = new Stationspec();
                                            $staspec->statable_id = $station->id;
                                            $staspec->message = $results['message'];
                                            $staspec->type = $results['type'];
                                        }
                                        $station->status = 2;
                                        $staspec->save();
                                    }
                                } else {
                                    if ($station->name != "N/A") {
                                        $results = array(
                                            "message" => "Name is N/A",
                                            "type" => "Signal",
                                            "station_id" => $station->id
                                        );
                                        if (!Stationspec::where('statable_id', '=', $station->id)->where('message', $results['message'])->exists()) {
                                            $staspec = new Stationspec();
                                            $staspec->statable_id = $station->id;
                                            $staspec->message = $results['message'];
                                            $staspec->type = $results['type'];
                                        }
                                        $station->status = 2;
                                        $staspec->save();
                                    } else {

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getCpeGain($station){

        if($station->model=="Rocket Prism 5AC Gen2")
        {
            return 28 ;
        }
        if($station->model=="Rocket M5 Titanium GPS")
        { return 27;
        }
        if($station->model=="Rocket M5")
        { return 27;
        }
        if($station->model=="Rocket 5AC Prism")
        { return 28;
        }
        if($station->model=="PowerBeam M5 400-ISO")
        { return 25;
        }
        if($station->model=="PowerBeam M5 400")
        { return 25;
        }
        if($station->model=="PowerBeam M5 300")
        { return 22;
        }
        if($station->model=="PowerBeam 5AC 400")
        { return 25;
        }
        if($station->model=="PowerBeam 5AC 300")
        { return 22;
        }
        if($station->model=="NanoStation M5")
        { return 16;
        }
        if($station->model=="NanoBridge M5")
        { return 22;
        }
        if($station->model=="NanoBeam M5 19")
        { return 22;
        }
        if($station->model=="LiteBeam M5")
        { return 23;
        }
        if($station->model=="Bullet M5")
        { return 25;
        }
        if($station->model=="AirGrid M5 HP")
        { return 23;
        }
        if($station->model=='"ePMP Elevate RM5-XW-V1"')
        { return 30;
        }
        if($station->model=='"ePMP Elevate PBE-M5-620-XW"')
        { return 29;
        }
        if($station->model=='"ePMP Elevate PBE-M5-400-XW"')
        { return 25;
        }
        if($station->model=='"ePMP Elevate PBE-M5-400-ISO-XW"')
        { return 25;
        }
        if($station->model=='"ePMP Elevate PBE-M5-300-XW"')
        { return 22;
        }
        if($station->model=='"ePMP Elevate NSlocoM5-XW"')
        { return 16;
        }
        if($station->model=='"ePMP Elevate NSlocoM2-XW"')
        { return 16;
        }
        if($station->model=='"ePMP Elevate NBE-M5-19-XW"')
        { return 19;
        }
        if($station->model=='"ePMP Elevate NBE-M5-16-XW"')
        { return 16;
        }
        if($station->model=='"5G Force 200 (ROW)"')
        { return 25;
        }
        if($station->model=='"5 GHz Force 190 Radio (ROW/ETSI)"')
        { return 22;
        }
        if($station->model=='"5 GHz Force 180 (ROW)"')
        { return 16;
        }
        if($station->model=='"5 GHz Connectorized Radio"')
        { return 30;
        }
        if($station->model=="NULL")
        { return 25;
        }
        if($station->model=="n/a")
        { return 25;
        }
        if($station->model=='"Unknown type"')
        { return 25;
        }

        return 25;
    }

}
