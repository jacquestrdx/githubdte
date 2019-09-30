<?php

namespace App\Http\Controllers;

use App\Devicetype;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Highsiteform;
use App\Location;
use App\Bwstaff;
use App\Hscontact;
use App\Device;
use App\Neighbor;
use App\Http\Controllers\DeviceController;
use Khill\Lavacharts\Lavacharts;


Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class NeighborController extends Controller
{

    public function index(){
        return view('neighbor.index');
    }

    public function getAllAjax()
    {
        $neighbors = Neighbor::with('device')->get();
        foreach ($neighbors as $neighbor) {
            $link = '<a href="/device/'.$neighbor->device->id.'">'.$neighbor->device->name."</a>";
            if ($neighbor->verified == 1){
                $verifiedicon = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green">1</i>';
            }else{
                $verifiedicon = '<i class="fa fa-times-circle-o" aria-hidden="true" style="color:red">0</i>';

            }
            if ($neighbor->ip > 0){
                $array[] = [$neighbor->id, $neighbor->ip, $neighbor->mac_address,$neighbor->interface, $neighbor->identity, $neighbor->platform,$link,$verifiedicon];
            }
        }

        return $array;
    }


}