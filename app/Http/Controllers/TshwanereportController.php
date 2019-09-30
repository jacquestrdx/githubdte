<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Tshwanereport;
use App\Bwstaff;
use App\Device;

Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class TshwanereportController extends Controller
{
    public function index(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','monthly')->first();

        return view('isizwe.reportweekly',compact('report'));
    }

    public function show($id){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','monthly')->first();
        return view('isizwe.reportmonthly',compact('report'));
    }

    public function fiztableWeekly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','weekly')->first();
        $array = array();
        foreach (json_decode($report->fiz_table) as $device){
            $array[] = [ $device['0'],$device['2'],( round((100 - $device['1'])*168/100,2)) , $device['1'] ];
        }
        return $array;
    }

    public function fiztableMonthly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','monthly')->first();
        $array = array();
        foreach (json_decode($report->fiz_table) as $device){
            $array[] = [ $device['0'],$device['2'],( round((100 - $device['1'])*720/100,2)) , $device['1'] ];
        }
        return $array;
    }

    public function latencytableMonthly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','monthly')->first();
        $array = array();
        foreach (json_decode($report->latency_table) as $device){
            $array[] = [ $device['0'], $device['1'],$device['2'] ];
        }
        return $array;
    }

    public function latencytableWeekly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','weekly')->first();
        $array = array();
        foreach (json_decode($report->latency_table) as $device){
            $array[] = [ $device['0'], $device['1'],$device['2'] ];
        }
        return $array;
    }

    public function devicetableWeekly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','weekly')->first();
        $array = array();
        foreach (json_decode($report->device_table) as $device){
            $array[] = [ $device->device,(round($device->total_downtime/60/60,1))." hrs",$device->uptime];
        }

        return $array;
    }
    public function devicetableMonthly(){
        $report = Tshwanereport::orderby('created_at','desc')->where('type','monthly')->first();
        $array = array();
        foreach (json_decode($report->device_table) as $device){
            $array[] = [ $device->device,(round($device->total_downtime/60/60,1))." hrs",$device->uptime];
        }

        return $array;
    }
}
