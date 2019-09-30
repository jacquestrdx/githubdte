<?php

namespace App;
use App\Client;

use App\Jacques\MikrotikLibrary;
use Illuminate\Database\Eloquent\Model;

class Pppoeclient extends Model
{

    protected $fillable = ['reason'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }
    public function statable()
    {
        return $this->belongsTo('App\Statable');
    }

    public static function doClientSpeedTest($id){
        $pppoes = Pppoeclient::where('statable_id',$id)->get();
        foreach ($pppoes as $pppoe){
            echo $pppoe->username." - ".$pppoe->vendor."\n";
            $themikrotiklibrary = new MikrotikLibrary();
            if($pppoe->vendor == "Routerboard.com"){
                $themikrotiklibrary->testClientSpeed($pppoe);
            }
        }
    }
    public static function checkOnlinePPPOES(){

        $query = 'select sum(num_sessions) from view_nas_active_sessions where nasipaddress like "196%"';
        $con=mysqli_connect(config('datatill_ip'),config('datatill_mysql_user'),config('datatill_mysql_password'),"freeradius");

        $result = mysqli_query($con,$query);
        if (FALSE === $result) die("Select sum failed: \n");
            $row = mysqli_fetch_row($result);
            $sum = $row[0];
            mysqli_free_result($result);
            mysqli_close($con);
        try{
            $device = Device::where('name','Openserve_clients')->first();
            $device->active_pppoe = $sum;
            $device->save();
        }catch (\Exception $e){

        }


        $pppoes = Pppoeclient::get();
        $date = new \DateTime;
        $namecounter = 0;
        $maccounter = 0;
        $date->modify('-60 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        foreach ($pppoes as $pppoe){
            try {

                if (($pppoe->updated_at < $formatted_date) AND ($pppoe->is_notified == "0")) {
                    $pppoe->is_online = 0;
                }
                $pos = strpos($pppoe->vendor, "Ubiquiti");
                if ($pos !== false) {
                    $mac = (preg_replace("/:/", " ", $pppoe->mac)) . " ";
                    $station = Statable::where('mac', $mac)->first();
                    if (isset($station->device)) {
                        $pppoe->statable_id = $station->id;
                        $maccounter++;
                    } else {
                        $pppoe->statable_id = "0";
                    }
                } else {
                    $pppoe->statable_id = "0";
                }
                $pppoe->save();
            }catch (\Exception $e){
                echo $e;
            }
            try{

            if (($pppoe->updated_at < $formatted_date) AND ($pppoe->is_notified == "0")){
                $pppoe->is_online = 0;
            }
                $station = Statable::where('name',trim($pppoe->username))->first();
                if (isset($station->device)) {
                    echo ($station->name." found \n");
                    $namecounter++;
                    $pppoe->statable_id = $station->id;
                }else{
                    $pppoe->statable_id = "0";
                }

            $pppoe->save();
            }catch(\Exception $e){

            }
        }



    }

    public static function findVIPClientNas(){
        $vipclients = Client::get();
        foreach($vipclients as $vipclient){
            $pppoeclient = Pppoeclient::where('username',$vipclient->username)->with('device')->first();
            if (isset($pppoeclient->device_id)) {
                $vipclient->location_id = $pppoeclient->device->location_id;
                $vipclient->save();
            }
        }
    }



}
