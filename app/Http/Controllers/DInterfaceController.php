<?php

namespace App\Http\Controllers;

use App\Bwstaff;
use App\Charts\LineChart;
use App\Device;
use App\Interfacelog;
use App\InterfaceWarning;
use App\Jacques\InterfacesLibrary;
use App\Jacques\MikrotikLibrary;
use App\Location;
use App\DInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DInterfaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interfaces = DInterface::get();

        return view('dinterface.index',compact('interfaces'));
    }

    public function getAjaxDataPerInterface($id){
        $interface = DInterface::with('device')->find($id);
        $array = $this->livePollTraffic($interface->device,$interface);
        return $array;
    }

    public function livePollTraffic($device,$interface){
        $themikrotiklibrary = new MikrotikLibrary();
        $traffic = $themikrotiklibrary->livePollInterface($device,$interface);
        $traffic = array(
            'rx' => $traffic[0],
            'tx' => $traffic[1],
        );
        return $traffic;
    }

    public function getLocationInterfaces($id){
        $location = Location::with('device')->find($id);
        foreach ($location->device as $device) {
            foreach ($device->interfaces as $interface) {
                $interfacearray[$interface->id] = $device->name . "-" . $interface->name;
            }
        }
        if (isset($interfacearray)){
            return $interfacearray;
        }else{
            return "No interfaces found";
        }
    }

    public function getDeviceInterfaces($id){
        $device = Device::find($id);
        foreach($device->interfaces as $interface){
            $array[$interface->id] = $interface->name;
        }
        if(isset($array)){
            return $array;
        }else{
            $return ["NO Interfaces Found"];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

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

    public function edit($id){
        $dinterface = DInterface::find($id);
        return view('dinterface.edit',compact('dinterface'));
    }

    public function acknowledgeinterface($id){
        $dinterface = DInterface::find($id);
        $dinterface->acknowledged = 1;
        $dinterface->save();
        $message = "$dinterface->name Acknowledged!!";
        return redirect("dinterface/$dinterface->id")->with('message');

    }

    public function update($id)
    {
        $input = Input::all();
        DInterface::find($id)->update($input);
        $dinterface = DInterface::find($id);
        //return Redirect::back()->with('message','bwstaff updated.');
        return redirect("dinterface/$dinterface->id")->with('message');
    }


    public function destroy($id)
    {

    }

    public function delete($id){
        $interface = DInterface::find($id);
        $deleted = \DB::delete('delete from interfaces where interfaces.id ='.$id);
        $command = "rm /var/www/html/dte/rrd/interfaces/".$interface->device->id."/$interface->id";
        exec($command);
        $command = "rm /var/www/html/dte/rrd/interfaces/".$interface->device->id."/$interface->default_name";
        exec($command);
    }
    public function show($id){
            $dinterface = DInterface::find($id);
            $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];
            $step = 300;
            $start = time() - (7 * 24 * 60 * 60);
            $end = time();
            $packets_array = array();
            $finals = array();
            $interfacelogs = Interfacelog::where('dinterface_id',$id)->orderby('created_at','DESC')->get();
            $interfacewarnings = InterfaceWarning::where('dinterface_id',$id)->orderby('created_at','DESC')->get();
            $rrdFile = "/var/www/html/dte/rrd/interfaces/" . trim($dinterface->device_id) . "/" . trim($dinterface->default_name) . ".rrd";

        try {
                $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution", config('rrd.step'), "--start", (time() - 6000), "--end", (time() - 350)));
                if(isset($result['data'])) {

                    foreach ($result["data"]["rxvalue"] as $key => $value) {
                        $labels[] = $key;
                    }
                    foreach ($result["data"]["rxvalue"] as $key => $value) {
                        if (is_finite($value)) {
                            $array['rxvalue'][] = $value;
                        } else {
                            $array['rxvalue'][] = 0;
                        }
                    }
                    foreach ($result["data"]["Availabilty"] as $key => $value) {
                        if (is_finite($value)) {
                            $array['availabilty'][] = $value;

                        } else {
                            $array['availabilty'][] = 0;
                        }
                    }
                    foreach ($result["data"]["txvalue"] as $key => $value) {
                        if (is_finite($value)) {
                            $array['txvalue'][] = $value;
                        } else {
                            $array['txvalue'][] = 0;
                        }
                    }
                    foreach ($result["data"]["ifInErrors"] as $key => $value) {
                        if (is_finite($value)) {
                            $array['ifInErrors'][] = $value;
                        } else {
                            $array['ifInErrors'][] = 0;
                        }
                    }
                    foreach ($result["data"]["ifOutErrors"] as $key => $value) {
                        if (is_finite($value)) {
                            $array['ifOutErrors'][] = $value;
                        } else {
                            $array['ifOutErrors'][] = 0;
                        }
                    }

                    foreach ($labels as $key => $value) {
                        if (isset($labels[$key + 1])) {
                            $array['timestamps'][] = $labels[$key + 1] - $value;
                        }
                    }
                    foreach ($labels as $value) {
                        $formatted_timestamps[] = date("F-j-Y g:i a", $value);
                    }


                    foreach ($array['ifInErrors'] as $key => $value) {
                        if (isset($array['ifInErrors'][$key + 1])) {
                            $error_array['ifInErrors'][] = $array['ifInErrors'][$key + 1] - $value;
                        }
                    }

                    foreach ($array['ifOutErrors'] as $key => $value) {
                        if (isset($array['ifOutErrors'][$key + 1])) {
                            $error_array['ifOutErrors'][] = $array['ifOutErrors'][$key + 1] - $value;
                        }
                    }

                    foreach ($array['rxvalue'] as $key => $value) {
                        if (isset($array['rxvalue'][$key + 1])) {
                            if (($array['rxvalue'][$key + 1] == 0) or ($value == 0) or ($array['rxvalue'][$key + 1] == $value)) {
                                $finals['rxvalue'][] = 0;
                            } else {
                                $rxvalue = $array['rxvalue'][$key + 1] - $value;
                                $final = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                                $finals['rxvalue'][] = $final;

                            }
                        }
                    }

                    foreach ($array['txvalue'] as $key => $value) {
                        if (isset($array['txvalue'][$key + 1])) {
                            if (($array['txvalue'][$key + 1] == 0) or ($value == 0) or ($array['txvalue'][$key + 1] == $value)) {
                                $finals['txvalue'][] = 0;
                            } else {
                                $rxvalue = $array['txvalue'][$key + 1] - $value;
                                $finals['txvalue'][] = round($rxvalue * 8 / $array['timestamps'][$key] / 1024 / 1024, 2);
                            }
                        }
                    }

                    $interface_chart = new LineChart();
                    $interface_chart->labels($formatted_timestamps);
                    $count = 0;
                    foreach ($finals as $key => $result) {
                        $interface_chart_render = true;
                        $interface_chart->dataset($key . " (Mbps)", "line", $result)
                            ->color($colorarray[$count])
                            ->lineTension(0)
                            ->options([
                                'pointRadius' => '0',
                            ]);
                        $count++;
                    }
                    $options_interface_chart = array(
                        "responsive" => "true",
                        "displayLegend" => "true"
                    );
                    $interface_chart->options($options_interface_chart);
                    $interface_chart->loaderColor('blue');

                    $interface_errors_chart = new LineChart();
                    $interface_errors_chart->labels($formatted_timestamps);

                    $count = 0;
                    $interface_errors_chart_render = true;
                    $interface_errors_chart->dataset("IfInErrors" . " ()", "line", $error_array['ifInErrors'])
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                    $interface_errors_chart->dataset("IfOutErrors" . " ()", "line", $error_array['ifOutErrors'])
                        ->color($colorarray[$count])
                        ->lineTension(0)
                        ->options([
                            'pointRadius' => '1',
                        ]);
                    $count++;

                    $interface_status_chart = new LineChart();
                    if (array_key_exists('availabilty', $array)) {
                        $interface_status_chart_render = true;
                        $interface_status_chart->labels($formatted_timestamps);
                        $interface_status_chart->dataset("Availability (%)", "line", $array['availabilty'])
                            ->color($colorarray[$count])
                            ->lineTension(0)
                            ->options([
                                'pointRadius' => '0',
                            ]);
                        $options_interface_chart = array(
                            "responsive" => "true",
                            "displayLegend" => "true"
                        );
                        $interface_status_chart->options($options_interface_chart);
                        $interface_status_chart->loaderColor('blue');
                    }

                    $interface_packets_chart = new LineChart();
                    $interface_packets_chart->labels($labels);

                    foreach ($packets_array as $key => $result) {
                        $interface_packets_chart_render = true;
                        $interface_packets_chart->dataset($key . " (PPs)", "line", $result)
                            ->color($colorarray[$count])
                            ->lineTension(0)
                            ->options([
                                'pointRadius' => '1',
                            ]);
                        $count++;
                    }
                    $options_interface_chart = array(
                        "responsive" => "true",
                        "displayLegend" => "true"
                    );
                    $interface_packets_chart->options($options_interface_chart);
                    $interface_packets_chart->loaderColor('blue');

                    return view('dinterface.show', compact('interfacewarnings','interfacelogs', 'interface_packets_chart', 'interface_packets_chart_render', 'dinterface', 'interface_status_chart', 'interface_status_chart_render', 'interface_errors_chart', 'interface_errors_chart_render', 'interface_chart', 'interface_chart_render'));
                }else{
                    return view('dinterface.show', compact('interfacewarnings','interfacelogs', 'dinterface'));
                }
                } catch (Exception $e) {
            }
        }
}
