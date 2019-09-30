<?php

namespace App\Http\Controllers;

use App\Charts\LineChart;
use App\Customsnmpoid;
use Illuminate\Http\Request;
use App\Device;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class CustomsnmpoidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customoid.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('customoid.create',compact('id'));
    }

    public function test(Request $request){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $results = array();
        $input = Input::all();
        if($input['action'] == "Test"){
            try {
                $device = Device::find($input['device_id']);
                $results = snmprealwalk($device->ip, $input['snmp_community'], $input['oid_to_poll']);
                \Session::flash('status', 'Device successfully Test! '.json_encode($results));
                \Session::flash('notification_type', 'Success');
            }catch(\Exception $e){
                \Session::flash('status', 'Device FAILED!');
                \Session::flash('notification_type', 'Error');
            }

            return view('customoid.createaftertest',compact('results','input'));
        }else{
            $customoid =  new Customsnmpoid();
            $customoid->value_name = $input['value_name'];
            $customoid->snmp_community = $input['snmp_community'];
            $customoid->device_id = $input['device_id'];
            $customoid->oid_to_poll = $input['oid_to_poll'];
            $customoid->save();
        }
        $device = Device::find($customoid->device_id);
        return redirect("/device/$device->id    ");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
        $device = Device::find($id);
        if( ($device->devicetype_id =="6") or ($device->devicetype_id =="7")) {
            foreach ($device->customoid as $customsnmpoid) {
                $rrdFile = "/var/www/html/dte/rrd/ciscos/custom/" . $customsnmpoid->value_name . ".rrd";
                $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time() - 86400), "--end", (time() - 350)));
                $labels = array();
                $formatted_timestamps = array();
                if ($result != false) {
                    foreach ($result["data"] as $key => $value) {
                        $labels = array();
                        foreach ($value as $time => $row) {
                            $set = 0;
                            if ($row > 1000) {
                                $array[$customsnmpoid->value_name][] = 0;
                            } else {
                                if (is_finite($row)) {
                                        $array[$customsnmpoid->value_name][] = $row ;
                                } else {
                                    $array[$customsnmpoid->value_name][] = 0;
                                }
                            }
                            $labels[] = $time;
                        }
                    }
                    foreach ($labels as $value) {
                        $formatted_timestamps[] = date("F-j-Y g:i a", $value);
                    }
                }
            }
        }
        $count = 0;
        foreach ($array as $key => $values) {
            ${$key . "station_chart"} = new LineChart();
            ${$key . "station_chart"}->labels($formatted_timestamps);
            ${$key . "station_chart"}->dataset(ucfirst($key), "line", $values)
                ->color($colorarray[$count])
                ->lineTension(0)
                ->options([
                    'pointRadius' => '1',
                ]);
            $count++;
            $options_interface_chart = array(
                "title" => array(
                    "display" => "true",
                    "text" => "$key"
                ),
                "responsive" => "true",
                "displayLegend" => "true"
            );
            ${$key . "station_chart"}->options($options_interface_chart);
            ${$key . "station_chart"}->loaderColor('blue');
            $charts['chart'][] = ${$key . "station_chart"};
            $charts['interface'][] = ucfirst($key);
        }
        return view('device.graphsNew', compact('charts', 'statable','device'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customoid = Customsnmpoid::find($id);
        return view('customoid.edit',compact('customoid'));
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
