<?php

namespace App;

use App\Backhaul;
use App\InterfaceWarning;
use Illuminate\Database\Eloquent\Model;

class DInterface extends Model
{
    protected $table = 'interfaces';

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function backhaul()
    {
        return $this->hasMany('App\Backhaul');
    }

    public function interfacewarning()
    {
        return $this->hasMany('App\InterfaceWaring');
    }

    public function interfacelogs(){
        return $this->hasMany('App\Interfacelog');
    }


    public static function ResetThresholdsToday(){
        \DB::table('interfaces')->where('device_id',">" ,"0")->update(['threshholds_today' => "0"]);
    }
    public function getInterfaceName($id){
        $dinterface = DInterface::find($id);
        return ($dinterface->name);
    }

    public function getDeviceName($id){
        $dinterface = DInterface::find($id);
        return ($dinterface->device->name);
    }

    protected $fillable = [ "id","name","default_name","mac_address","type","threshhold","last_link_down_time","last_link_up_time","mtu","actual_mtu","running","disabled","device_id","updated_at","created_at"];

    public static function checkPortLinks(){
//        $interfaces = DInterface::get();
//
//        foreach ($interfaces as $interface){
//            $message = $interface->device->name." ".$interface->name." changed from ". $interface->previous_running_state." to ".$interface->running;
//            if($interface->running!=$interface->previous_running_state){
//                echo "Backhaul where $interface->name \n";
//                if(Backhaul::where('dinterface_id',$interface->id)->exists()){
//                    echo "Interfacewarning where $interface->name \n";
//                    if(!InterfaceWarning::where('dinterface_id',$interface->id)->where('message',$message)->exists()){
//                        echo " \n LOOP 1 \n";
//                        $interfacewarning =  new InterfaceWarning;
//                        $interfacewarning->dinterface_id = $interface->id;
//                        $interfacewarning->message = $message;
//                        $interfacewarning->time = new \DateTime();
//                        $interfacewarning->save();
//                    }else{
//                        $interfacewarning = InterfaceWarning::where('dinterface_id',$interface->id)->where('message',$message)->first();
//                        $interfacewarning->dinterface_id = $interface->id;
//                        $interfacewarning->message = $message;
//                        $interfacewarning->time = new \DateTime();
//                        $interfacewarning->save();
//                    }
//                }
//            }
//            if($interface->link_speed!=$interface->previous_link_speed) {
//                $message = $interface->device->name . " " . $interface->name . " changed from " . $interface->previous_link_speed . " to " . $interface->link_speed;
//                echo "Backhaul where $interface->name \n";
//                if(Backhaul::where('dinterface_id',$interface->id)->exists()){
//                    echo "Interfacewarning where $interface->name \n";
//                    if (!InterfaceWarning::where('dinterface_id', $interface->id)->where('message', $message)->exists()) {
//                        echo " \n LOOP 1 \n";
//                        $interfacewarning = new InterfaceWarning;
//                        $interfacewarning->dinterface_id = $interface->id;
//                        $interfacewarning->message = $message;
//                        $interfacewarning->time = new \DateTime();
//                        $interfacewarning->save();
//                    } else {
//                        $interfacewarning = InterfaceWarning::where('dinterface_id', $interface->id)->where('message', $message)->first();
//                        $interfacewarning->dinterface_id = $interface->id;
//                        $interfacewarning->message = $message;
//                        $interfacewarning->time = new \DateTime();
//                        $interfacewarning->save();
//                    }
//                }
//            }
//        }
    }



}
