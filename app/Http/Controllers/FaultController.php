<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fault;
use App\Http\Requests;

use App\Device;
use App\Location;
use App\Client;
Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;
use App\DeviceUpdateController;
use App\Devicetype;
class FaultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function acknowledge($id)
    {
        $fault = Fault::find($id);
        return view('acknowledge.add_fault', compact('fault'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function faultreportAJAX(){
        $faults = Fault::get();
        foreach($faults as $fault){
            if($fault->status =="1"){
                $status = "<p style='color:red'> Active</p>";
                $time_ended ="";
            }else{
                $status = "<p style='color:green'> Resolved</p>";
                $time_ended = date_format($fault->updated_at,"Y/m/d H:i:s");
            }
            $array[] = [$fault->device->name,'<a href="/device/'.$fault->device_id.'">'.$fault->device->ip.'</a>',$fault->description,$status,date_format($fault->created_at,"Y/m/d H:i:s"),$time_ended];
        }
        return $array;
    }

    public function faultreportNoSnmpAJAX(){
        $faults = Fault::where('description',"No SNMP Response")->where('status',"1")->get();
        foreach($faults as $fault){
            if($fault->status =="1"){
                $status = "<p style='color:red'> Active</p>";
                $time_ended ="";
            }else{
                $status = "<p style='color:green'> Resolved</p>";
                $time_ended = date_format($fault->updated_at,"Y/m/d H:i:s");
            }
            $array[] = [$fault->device->name,'<a href="/device/'.$fault->device_id.'">'.$fault->device->ip.'</a>',$fault->description,$status,date_format($fault->created_at,"Y/m/d H:i:s"),$time_ended];
        }
        return $array;
    }

    public function faultreport(){
        $faults = Fault::get();
        return view('device.faultreport',compact('faults'));
    }
    public function faultreportNoSnmp(){
        return view('device.faultreportnosnmp',compact('faults'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
