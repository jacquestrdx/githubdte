<?php

namespace App\Http\Controllers;

use App\UserNotification;
use Illuminate\Http\Request;
use App\InterfaceWarning;
use App\Http\Requests;

class InterfaceWarningController extends Controller
{
    public function index(){
        return view('interface_warnings.index');
    }

    public function getAllAjax(){
        $interfacewarnings = InterfaceWarning::with('dinterface')->get();
        foreach ($interfacewarnings as $interfacewarning){
            $deviceid = $interfacewarning->dinterface->device_id;
            $devicename = $interfacewarning->dinterface->device->name;
            $interfacename = $interfacewarning->dinterface->name;
            $acknowledge = "<a href='/interfacewarning/acknowledge/$interfacewarning->id'>Acknowledge</a>";
            $device = "<a href='/device/$deviceid'>$devicename</a>";
            $interface = "<a href='/dinterface/$interfacewarning->dinterface_id'>$interfacename</a>";
            $array[] = [$device,$interface,$interfacewarning->message,$interfacewarning->time,$acknowledge];
        }
        return $array;
    }

    public function acknowledge($id){
        $interfacewarning = InterfaceWarning::find($id);
        $interfacewarning->delete();
        $usernotifications = UserNotification::where('interfacewarning_id','!=','0')->where('interfacewarning_id',$id)->get();
        foreach ($usernotifications as $usernotification){
            $deleted = \DB::delete('delete from usernotifications where interfacewarning_id ='.$id);
            $deleted = \DB::delete('delete from interface_warning where id ='.$id);
        }
        return redirect("interfacewarnings");
    }

    public function delete($id){
        $interfacewarning = InterfaceWarning::find($id);
        $interfacewarning->ack = 1;
        $interfacewarning->save();
        return redirect("/blackboard");
    }


}
