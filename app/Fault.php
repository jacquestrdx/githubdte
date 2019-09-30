<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Device;
use App\Jacques\RouterosAPI;


class Fault extends Model
{
    function device(){
        return $this->belongsTo('App\Device');
    }

    public static function getFaultyDevices()
    {
        $devices = Device::get();
        foreach ($devices as $device) {
            try{
                $description = "Device rebooted";
                if($device->uptime < 550){
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if (isset($fault->status)){
                        if($fault->status == 0){
                            $new_fault = new Fault();
                            $new_fault->description = $description;
                            $new_fault->device_id = $device->id;
                            $new_fault->status = 1;
                            $new_fault->save();
                        }else{
                        }
                    }else{
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    }
                } else {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if(isset($fault->status)){
                        $fault->status = 0;
                        $fault->save();
                        //set fault to resolved
                    }
                    //set fault to resolved
                }
                Fault::checkDeviceforFaults($device);
            }catch (\Exception $e){
                echo $e;
            }
        }
    }

    public function acknowledgements()
    {
        return $this->hasMany('App\Acknowledgement');
    }



    public static function checkDeviceforFaults($device)

    {
        try{
            if (($device->devicetype_id == '1') or ($device->devicetype_id == '15') ){
                Fault::checkMikrotik($device);
            } // Mikrotik Checks end

            if ( ($device->devicetype_id == '2') or ($device->devicetype_id == '22') ) {
                Fault::checkUbiquiti($device);
            }
            //Ubituiti Sectors end

            if (($device->devicetype_id == '10') OR ($device->devicetype_id == '11') OR ($device->devicetype_id == '11')) {
                Fault::checkWireless($device);
            }
        }catch (\Exception $e){
            echo $e;
        }

    }

    public static function checkWireless($device){
        $description = "Signal is less than -65";
        if (($device->signal < -65) AND ($device->signal != "-96")) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if (isset($fault->status)){
                if($fault->status == 0){
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }else{
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }
    }

    public static function checkUbiquiti($device){

        if ($device->active_stations >= "1") {
            if($device->devicetype_id =="22") {
                $faults = Fault::where('device_id', '=', $device->id)->where('description','CCQ is less than 85%')->get();
                foreach ($faults as $fault) {
                    if (!is_null($fault)) {
                        $fault->delete();  echo "Deleting.\n";

                    }
                }
            }else{
                $description = "CCQ is less than 85%";
                if ($device->avg_ccq <= 85) {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if (isset($fault->status)) {
                        if ($fault->status == 0) {
                            //create new fault
                            $new_fault = new Fault();
                            $new_fault->description = $description;
                            $new_fault->device_id = $device->id;
                            $new_fault->status = 1;
                            $new_fault->save();
                        } else {
                            //else do nothing
                        }
                    }else{
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    }
                } else {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if(isset($fault)){
                        $fault->status = 0;
                        $fault->save();
                        //set fault to resolved
                    }
                    //set fault to resolved
                }
            }

            $description = "More than 25 Stations!!";
            if ($device->active_stations > 25) {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if (isset($fault->status)) {
                    if ($fault->status == 0) {
                        //create new fault
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    } else {
                        //else do nothing
                    }
                }else{
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }
            } else {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if(isset($fault)){
                    $fault->status = 0;
                    $fault->save();
                    //set fault to resolved
                }
                //set fault to resolved
            }

            $description = "No Stations!!";
            if ($device->active_stations <= 0) {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if (isset($fault->status)) {
                    if ($fault->status == 0) {
                        //create new fault
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    } else {
                        //else do nothing
                    }
                }else{
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }
            } else {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if(isset($fault)){
                    $fault->status = 0;
                    $fault->save();
                    //set fault to resolved
                }
                //set fault to resolved
            }

            $description = "Channel is on $device->channel";
            if ((trim($device->channel) == "30")) {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if (isset($fault->status)) {
                    if ($fault->status == 0) {
                        //create new fault
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    } else {
                        //else do nothing
                    }
                }else{
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }
            } else {
                $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if(isset($fault)){
                    $fault->status = 0;
                    $fault->save();
                    //set fault to resolved
                }
                //set fault to resolved
            }

            $description = "Channel is on $device->channel";
            if (($device->devicetype_id == "2") OR ($device->devicetype_id == "22")){
                if ((trim($device->channel) == "40")) {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if (isset($fault->status)) {
                        if ($fault->status == 0) {
                            //create new fault
                            $new_fault = new Fault();
                            $new_fault->description = $description;
                            $new_fault->device_id = $device->id;
                            $new_fault->status = 1;
                            $new_fault->save();
                        } else {
                            //else do nothing
                        }
                    }else{
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    }
                } else {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if(isset($fault)){
                        $fault->status = 0;
                        $fault->save();
                        //set fault to resolved
                    }
                    //set fault to resolved
                }
            }

            //Fault::checkStations($device);

        }else {
//                echo "No snmp found on ".$device->name." ".$device->id." - skipping\n";
            $faults = Fault::where('device_id', '=', $device->id)->get();
            foreach ($faults as $fault) {
                if (!is_null($fault)) {
                    $fault->delete();  echo "Deleting.\n";

                }
            }
        }
    }

    public static function checkStations($device){
        $date = new \DateTime;
        $date->modify('-500 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $description = "Blank";

        foreach ($device->statables as $statable) {
                if($statable->updated_at > $formatted_date){

                $description = "Client $statable->name ($statable->mac) $statable->id" . " has CCQ of less than 85%";

                if ($statable->ccq <= "85") {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if (isset($fault->status)) {
                        if ($fault->status == 0) {
                            //create new fault
                            $new_fault = new Fault();
                            $new_fault->description = $description;
                            $new_fault->device_id = $device->id;
                            $new_fault->status = 1;
                            $new_fault->save();
                        } else {
                            //else do nothing
                        }
                    }else{
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    }
                } else {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if(isset($fault)){
                        $fault->status = 0;
                        $fault->save();
                        //set fault to resolved
                    }
                    //set fault to resolved
                }

                $description = "Client $statable->name ($statable->mac) $statable->id" . " has signal weaker than -70";
                if ($statable->signal < "-70") {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if (isset($fault->status)) {
                        if ($fault->status == 0) {
                            //create new fault
                            $new_fault = new Fault();
                            $new_fault->description = $description;
                            $new_fault->device_id = $device->id;
                            $new_fault->status = 1;
                            $new_fault->save();
                        } else {
                            //else do nothing
                        }
                    }else{
                        $new_fault = new Fault();
                        $new_fault->description = $description;
                        $new_fault->device_id = $device->id;
                        $new_fault->status = 1;
                        $new_fault->save();
                    }
                } else {
                    $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                    if(isset($fault)){
                        $fault->status = 0;
                        $fault->save();
                        //set fault to resolved
                    }
                    //set fault to resolved
                }
            }
        }
    }

    public static function checkMikrotik($device){
        $description = "Device Cpu is higher than 60%";
        echo "$device->name CPU usage is $device->cpu \n";
        if ($device->cpu >= 60) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if (isset($fault->status)) {
                if ($fault->status == 0) {
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                } else {
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }

        }
        $description = "PSU 1 is down";
        if ($device->psu1 == 0) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault->status)) {
                if ($fault->status == 0) {
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                } else {
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        }else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }

        $description = "PSU 2 is down";
        if ($device->psu2 == 0) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault->status)) {
                    if($fault->status == 0){
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }else{
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }

        $description = "Device 2011 has too many PPPOE clients";
        if (($device->active_pppoe >= 55) AND (preg_match("/2011/", $device->model, $output))) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault->status)) {
                    if($fault->status == 0){
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }else{
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }


        $description = "Device memory is too full";
        echo "$device->name memory usage is $device->used_memory \n";
        if ($device->used_memory >= 75) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
                if(isset($fault->status)) {
                    if($fault->status == 0){
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }else{
                    //else do nothing
                }
            }else{
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }


        $description = "Device temperature is higher than 65 C";
        if ($device->temp >= 65) {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if (isset($fault->status)) {
                if ($fault->status == 0) {
                    //create new fault
                    $new_fault = new Fault();
                    $new_fault->description = $description;
                    $new_fault->device_id = $device->id;
                    $new_fault->status = 1;
                    $new_fault->save();
                } else {
                    //else do nothing
                }
            }else{
                $new_fault = new Fault();
                $new_fault->description = $description;
                $new_fault->device_id = $device->id;
                $new_fault->status = 1;
                $new_fault->save();
            }
        } else {
            $fault = Fault::where('description',$description)->where('device_id',$device->id)->orderBy('updated_at','DESC')->first();
            if(isset($fault)){
                $fault->status = 0;
                $fault->save();
                //set fault to resolved
            }
            //set fault to resolved
        }
    }

    public function getAcknowledgementNote()
    {

        $acknowledgement = Acknowledgement::where('fault_id', $this->id)->where('active', "1")->first();
        if (is_array($acknowledgement)) {
            if (count($acknowledgement)) {
                return $acknowledgement->ack_note;
            } else {
                return "";
            }
        }
    }

    public function getAckUser()
    {
        $acknowledgement = Acknowledgement::where('fault_id',$this->id)->where('active',"1")->first();
        if (is_array($acknowledgement)){
            if (count($acknowledgement)){
                $user_id = $acknowledgement->user_id;
            }else $user_id = "";

            $user = User::where('id', $user_id)->first();
            if (count($user)){
                return $user->name;
            }else return "";
        }
    }

    public static function ResetFaults(){
    }


    }
