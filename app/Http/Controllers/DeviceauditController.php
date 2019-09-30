<?php

namespace App\Http\Controllers;

use App\Deviceaudit;
use Illuminate\Http\Request;
use App\Job;
use App\Location;
use Illuminate\Support\Facades\Input;


use App\Http\Requests;

class DeviceauditController extends Controller
{
    public function index(){
        $user = \Auth::user();
        if ($user->user_type=="admin"){
            return view('deviceaudit.index');
        }else{
            return "Unauthorized access";
        }
    }

    public function getAllAjax(){
        $date        = new \DateTime;
        $date->modify('-1 week');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $deviceaudits = Deviceaudit::where('created_at','>',$formatted_date)->get();

        foreach ($deviceaudits as $deviceaudit){
            $array[] = [
                $deviceaudit->user->name,
                $deviceaudit->device_id,
                $deviceaudit->action,
                $deviceaudit->device_ip,
                $deviceaudit->device_name,
                $deviceaudit->created_at->format('Y-m-d H:i:s')
            ];
        }
        return $array;
    }

}
