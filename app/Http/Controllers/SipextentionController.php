<?php

namespace App\Http\Controllers;

use App\Queue;
use App\Sipextention;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Location;
use App\Bwstaff;
use App\Device;

Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class SipextentionController extends Controller
{


    public function getStatusNew(){
        $failedregister= SipextentionController::getFailedRegistrations();
        $temp = Sipextention::getActive();
        $temp = preg_replace('/\n/',":",$temp);
        $count = 0;
        $lines = explode(':',$temp);

        foreach($lines as $line){
            $line = preg_replace('/\s+/',",",$line);
            $array[] = explode(',',$line);
        }

        unset($array["0"]);
        $lastelement = sizeof($array) ;
        unset($array[$lastelement]);

        foreach ($array as $value){
            $final[$value[0]] = array(
                "status" => $value[1],
                "name" => ""
            );
        }

        $sipextentions = Sipextention::get();
        foreach ($sipextentions as $sipextention) {
            if(array_key_exists($sipextention->ext,$final)){
                $final[$sipextention->ext]["name"] = $sipextention->name;
                $final[$sipextention->ext]["status"] = Sipextention::determinestatus($final[$sipextention->ext]["status"]);
            }
        }
        foreach ($final as $key=>$value){
            $newarray[$count] = array(
                "status" => $value['status'],
                "name" => $value['name'],
                "ext" => $key,
            );
            $count++;
        }
        
        foreach($newarray as $key=>$row){
            if($row['status']=='0/0/0'){
                unset($newarray[$key]);
            }
        }

        foreach($newarray as $key=>$item){
            if(array_key_exists($item['ext'],$failedregister)){
                unset($newarray[$key]);
            }
        }

        $counter =0;
        foreach ($newarray as $row)
        {
            $finalarray[$counter] = $row;
            $counter++;
        }
        return $finalarray;
    }

    public function index(){
        $queues = Queue::with('sipextentions')->get();
        $sipextentions = Sipextention::get();
        return view('sipextention.index',compact('queues'));
    }

    public function getActiveCalls(){
        return Sipextention::getActiveCalls();
    }

    public static function getFailedRegistrations(){
        $sipfailed = Sipextention::getRegistered();
        $temp = preg_replace('/\n/',":",$sipfailed);
        $count = 0;
        $lines = explode(':',$temp);

        foreach($lines as $line){
            $line = preg_replace('/\s+/',",",$line);
            $array[] = explode(',',$line);
        }

        unset($array["0"]);
        $lastelement = sizeof($array) ;
        unset($array[$lastelement]);

        foreach($array as $key=>$row){
            if (array_key_exists('5',$row)){
                if($row[5]=="OK"){
                    unset($array[$key]);
                }else{
                    if(preg_match('/\//',$row[0])){
                        $str =strtok($row[0], '/');
                        $first[$str] = strtok($row[0], '/');
                    }else{
                        $first[$row[0]] = $row[0];
                    }
                }
            }
        }

        return $first;

    }




}
