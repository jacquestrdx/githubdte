<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\System;

class SystemController extends Controller
{
    public function index(){
        $system = System::find(1);
        return view ('system.show',compact('system'));
    }

    public function edit($id){
        $system = System::find($id);
        return view ('system.edit',compact('system'));
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->user_type=="admin") {
            $input = Input::all();
            $system = System::find($id);
            $system->update($input);
            if(array_key_exists('smtp_use_auth',$input)){
                $system->smtp_use_auth = 1;
                $system->save();
            }else {
                $system->smtp_use_auth = 0;
                $system->save();
            }
            if(array_key_exists('include_hotspot',$input)){
                $system->include_hotspot = 1;
                $system->save();
            }else{
                $system->include_hotspot = 0;
                $system->save();
            }
            $system->save();
        }
        return redirect("system")->with('system');

    }
    public function showRunning(){
        $command = 'ps aux | grep /usr/bin/php';
        exec($command,$finals);
        $results = array();
        foreach($finals as $key=> $result){
            preg_match('/\/var.*/',$result,$matches);
            if(array_key_exists('0',$matches)){
                $results[$key][] = $matches[0];
                preg_match('/\d\d\:\d\d/',$result, $matches2);
                $results[$key][] = $matches2[0];
            }
        }
        return view('system.showrunning',compact('results'));
    }

    public function PollingNr(){
        $finals = array();
        $command = 'ps aux | grep PollSpesificDeviceByID';
        exec($command,$finals);
        return(sizeof($finals)-2);
    }
}
