<?php

namespace App\Http\Controllers;

use App\Charts\LineChart;
use App\Device;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Statable;
use App\Location;

class StatableController extends Controller
{
    public function index(){
        return view ('statable.index');
    }

    public function showallAJAX(){
        $array = array();
        $statables = Statable::with('device')->get();
        foreach ($statables as $statable){
            if($statable->device->devicetype_id =="29"){
                $qam = array(
                    "0" => "0",
                    "1" => "4-QAM-L",
                    "2" => "4-QAM-M",
                    "3" => "4-QAM-H",
                    "4" => "16-QAM-L",
                    "5" => "16-QAM-H",
                    "6" => "64-QAM",
                    "7" => "128-QAM",
                    "8" => "265-QAM",
                    "9" => "512-QAM",
                    "10" => "1024-QAM-L",
                    "11" => "1024-QAM-H"
                );
                $array[] = array(
                    $statable->id,
                    "<a href='/statables/pergraph/".$statable->id."'>".$statable->mac."</a>",
                    "<a href='/statables/pergraph/".$statable->id."'>".$statable->mac."</a>",
                    $statable->ip,
                    ($statable->txsignal/100)." / ".($statable->rxsignal/100),
                    $statable->distance,
                    $statable->model,
                    $qam[$statable->rx_rate]." / ".$qam[$statable->tx_rate],
                    "<a href='/device/".$statable->device->id."'>".$statable->device->name."</a>",
                    "N/A"
                );
            }else{
                if($statable->status == 3){
                    $array[] = [
                        "<p style='color:red'>".$statable->id."</p>",
                        "<p style='color:red'>".$statable->name."</p>",
                        "<p style='color:red'>".$statable->mac."</p>",
                        "<p style='color:red'>".$statable->ip."</p>",
                        "<p style='color:red'>". $statable->signal."</p>",
                        "<p style='color:red'>".$statable->distance."</p>",
                        "<p style='color:red'>".$statable->model."</p>",
                        "<p style='color:red'>".$statable->rates."</p>",
                        "<a href='/device/$statable->device_id'>".$statable->device->ssid."</a>",
                        "<p style='color:red'>".'<a href="/stationspec/'.$statable->id.'">Out Of Spec'."</a></p>",
                    ];
                }
                if($statable->status == 2){
                    $array[] = [
                        "<p style='color:orange'>".$statable->id."</p>",
                        "<p style='color:orange'>".$statable->name."</p>",
                        "<p style='color:orange'>".$statable->mac."</p>",
                        "<p style='color:orange'>".$statable->ip."</p>",
                        "<p style='color:orange'>". $statable->signal."</p>",
                        "<p style='color:orange'>".$statable->distance."</p>",
                        "<p style='color:orange'>".$statable->model."</p>",
                        "<p style='color:orange'>".$statable->rates."</p>",
                        "<a href='/device/$statable->device_id'>".$statable->device->ssid."</a>",
                        "<p style='color:orange'>".'<a href="/stationspec/'.$statable->id.'">Close to problems'."</a></p>",
                    ];
                }
                if($statable->status == 0){
                $array[] = [
                    "<p style='color:green'>".$statable->id."</p>",
                    "<p style='color:green'>".$statable->name."</p>",
                    "<p style='color:green'>".$statable->mac."</p>",
                    "<p style='color:green'>".$statable->ip."</p>",
                    "<p style='color:green'>". $statable->signal."</p>",
                    "<p style='color:green'>".$statable->distance."</p>",
                    "<p style='color:green'>".$statable->model."</p>",
                    "<p style='color:green'>".$statable->rates."</p>",
                    "<a href='/device/$statable->device_id'>".$statable->device->ssid."</a>",
                    "<p style='color:green'>"."Excellent"."</p>",
                ];
            }
            }
        }
        return $array;
    }

    public function showLocationAjax($id){
        $date        = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $location = Location::find($id);
        $statables = \DB::select('SELECT statables.name, statables.mac,statables.ip, statables.updated_at,statables.distance, statables.latency, statables.signal, statables.rates, devices.ssid, devices.id, statables.time FROM `statables` 
inner join devices on devices.id = statables.device_id 
inner join locations on locations.id = devices.location_id 
where locations.id = '.$id);
        foreach ($statables as $statable){
            if ($statable->updated_at > $formatted_date){
                $connected = '<i class="fa fa-check-circle"
                                       aria-hidden="true"
                                       style="color:green"></i>';
            }else{
                $connected = "";
            }

            $array[] = [
                $statable->name,
                $statable->mac,
                $statable->ip,
                $statable->latency,
                $statable->signal,
                $statable->distance,
                $statable->rates,
                "<a href='/device/$statable->id'>"."$statable->ssid"."</a>",
                $statable->time,
                $connected,
            ];
        }
        return $array;
    }

    public function showInfracomStationGraphsOld($id){
        $statable = Statable::find($id);
        $qam = array(
            "0" => "0",
            "1" => "4.1",
            "2" => "4.2",
            "3" => "4.3",
            "4" => "16.1",
            "5" => "16.2",
            "6" => "64",
            "7" => "128",
            "8" => "265",
            "9" => "512",
            "10" => "1024.1",
            "11" => "1024.2"
        );
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];

        $client = new \crodas\InfluxPHP\Client(
            "localhost" /*default*/,
            8086 /* default */,
            "root" /* by default */,
            "root" /* by default */
        );
        $db = $client->dte;
        $array =array();
        $finals = array();

            $labels = array();
            $query = "SELECT * FROM statables where host ='" . $statable->id ."' and time > now() -2d order by time asc limit 300";
            $stats = $db->query($query);
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $newtime = preg_replace('/T/',' ',$stat->time);
                    $newtime = preg_replace('/Z/','',$newtime);
                    $format = 'Y-m-d H:i:s';
                    $date = \DateTime::createFromFormat($format, $newtime);
                    $date->modify('+2 hours');
                    $newtime = ($date->format('Y-m-d H:i:s'));
                    $stat->time = $newtime;
                    $labels[] = $newtime;
                    $results["distance"][]= $stat->distance;
                    $rxrate = $qam[$stat->rx_rate];
                    $results["rx_rate"][]= $rxrate;
                    $txrate = $qam[$stat->tx_rate];
                    $results['disconnects'][] = $stat->disconnects ?? 0;
                    $results["tx_rate"][]=  $txrate;
                    $results["rxsignal"][]= $stat->rxsignal/100;
                    $results["txsignal"][]= $stat->txsignal/100;
                    $results["rx_snr"][]= $stat->rx_snr/100;
                    $results["tx_snr"][]= $stat->tx_snr/100;
                    $results["tx_utilization"][]= $stat->tx_utilization/100;
                    $results["rx_utilization"][]= $stat->rx_utilization/100;
                    $results["tx_max_utilization"][]= $stat->tx_max_utilization/1000000;
                    $results["rx_max_utilization"][]= $stat->rx_max_utilization/1000000;
                }
        }

        $count = 0;

        foreach ($results as $key => $result) {
            ${$key."station_chart"} = new LineChart();
            ${$key."station_chart"}->labels($labels);
            unset($labels[count($labels)-1]);
            ${$key."station_chart"}->dataset(ucfirst($key), "line", $result)
                ->color($colorarray[$count])
                ->lineTension(0)
                ->options([
                    'pointRadius' => '1',
                ]);
            $count++;
            $options_interface_chart = array(
                "title" => array(
                    "display" => "true",
                    "text" => "$statable->mac - $statable->id"
                ),
                "responsive" => "true",
                "displayLegend" => "true"
            );
            ${$key."station_chart"}->options($options_interface_chart);
            ${$key."station_chart"}->loaderColor('blue');
            $charts['chart'][] =  ${$key."station_chart"};
            $charts['interface'][] = ucfirst($key);
        }
        return view('device.intracom.station',compact('charts','statable'));
    }

    public function showGraph($id)
    {
        $colorarray = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];

        $statable = Statable::find($id);
        if ($statable->device->devicetype_id == "29") {
            $qam = array(
                "0" => "0",
                "1" => "4.1",
                "2" => "4.2",
                "3" => "4.3",
                "4" => "16.1",
                "5" => "16.2",
                "6" => "64",
                "7" => "128",
                "8" => "265",
                "9" => "512",
                "10" => "1024.1",
                "11" => "1024.2"
            );
            $rrdFile = "/var/www/html/dte/rrd/intracoms/stations/" . $id . ".rrd";
            $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time() - 86400), "--end", (time() - 350)));
            $labels = array();
            $formatted_timestamps = array();
            if ($result != false) {
                foreach ($result["data"] as $key => $value) {
                    $labels = array();
                    foreach ($value as $time => $row) {
                        $set = 0;
                        if($row> 1000){
                            $array[$key][] = 0;
                        }else {
                            if (is_finite($row)) {
                                if ($key == "rxsignal") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "txsignal") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "rx_snr") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "tx_snr") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "tx_utilization") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "rx_utilization") {
                                    $array[$key][] = $row / 100;
                                    $set = 1;
                                }
                                if ($key == "rx_max_utilization") {
                                    $array[$key][] = $row / 1000000;
                                    $set = 1;
                                }
                                if ($key == "tx_max_utilization") {
                                    $array[$key][] = $row / 1000000;
                                    $set = 1;
                                }
                                if ($set = 0) {
                                    $array[$key][] = $row / 1000000;
                                    $set = 1;
                                }

                            } else {
                                $array[$key][] = 0;
                            }
                        }

                        $labels[] = $time;
                    }
                }
                foreach ($labels as $value) {
                    $formatted_timestamps[] = date("F-j-Y g:i a", $value);
                }
            }else{
                return "No RRD for station";
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
                        "text" => "$statable->mac - $statable->id"
                    ),
                    "responsive" => "true",
                    "displayLegend" => "true"
                );
                ${$key . "station_chart"}->options($options_interface_chart);
                ${$key . "station_chart"}->loaderColor('blue');
                $charts['chart'][] = ${$key . "station_chart"};
                $charts['interface'][] = ucfirst($key);
            }
            return view('device.intracom.station', compact('charts', 'statable'));
        }
        elseif (($statable->device->devicetype_id == "22") or ($statable->device->devicetype_id == "2")) {
            $statable = Statable::find($id);
            $rrdFile = "/var/www/html/dte/rrd/ubnts/stations/" . $id . ".rrd";
            $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution", config("rrd.step"), "--start", (time() - 86400), "--end", (time() - 350)));
            $labels = array();
            $formatted_timestamps = array();
            if ($result != false) {
                foreach ($result["data"] as $key => $value) {
                    $labels = array();
                    foreach ($value as $time => $row) {
                        if (is_finite($row)) {
                            $array[$key][] = $row;
                        } else {
                            $array[$key][] = 0;
                        }
                        $labels[] = $time;
                    }
                }
                unset($array["tx_bytes"]);
                unset($array["rx_bytes"]);
                foreach ($labels as $key => $value) {
                    if (isset($labels[$key + 1])) {
                        $finals['timestamps'][] = $labels[$key + 1] - $value;
                    }
                }
                foreach ($result["data"]["tx_bytes"] as $key => $value) {
                    if (is_finite($value)) {
                        $finals['tx_bytes'][] = $value;
                    } else {
                        $finals['tx_bytes'][] = 0;
                    }
                }
                foreach ($result["data"]["rx_bytes"] as $key => $value) {
                    if (is_finite($value)) {
                        $finals['rx_bytes'][] = $value;
                    } else {
                        $finals['rx_bytes'][] = 0;
                    }
                }
                foreach ($finals['rx_bytes'] as $key => $value) {
                    if (isset($finals['rx_bytes'][$key + 1])) {
                        if (($finals['rx_bytes'][$key + 1] == "0") or ($value == "0")) {
                            $array['rx_bytes'][] = 0;
                        } else {
                            $rxvalue = $finals['rx_bytes'][$key + 1] - $value;
                            $array['rx_bytes'][] = round($rxvalue * 8 / $finals['timestamps'][$key] / 1024 / 1024, 2);
                        }
                    }
                }


                foreach ($finals['tx_bytes'] as $key => $value) {
                    if (isset($finals['tx_bytes'][$key + 1])) {
                        if (($finals['tx_bytes'][$key + 1] == "0") or ($value == "0")) {
                            $array['tx_bytes'][] = 0;
                        } else {
                            $rxvalue = $finals['tx_bytes'][$key + 1] - $value;
                            $array['tx_bytes'][] = round($rxvalue * 8 / $finals['timestamps'][$key] / 1024 / 1024, 2);
                        }
                    }
                }


                foreach ($labels as $value) {
                    $formatted_timestamps[] = date("F-j-Y g:i a", $value);
                }
            }

            $count = 0;
            if(!isset($array)){return "No RRD Data";}
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
                            "text" => "$statable->mac - $statable->id"
                        ),
                        "responsive" => "true",
                        "displayLegend" => "true"
                    );
                    ${$key . "station_chart"}->options($options_interface_chart);
                    ${$key . "station_chart"}->loaderColor('blue');
                    $charts['chart'][] = ${$key . "station_chart"};
                    $charts['interface'][] = ucfirst($key);
                }
                return view('device.intracom.station', compact('charts', 'statable'));
            }
        elseif
            ($statable->device->devicetype_id == "17"){
            $rrdFile = "/var/www/html/dte/rrd/cambiums/stations/" . $statable->id . ".rrd";
            $result = rrd_fetch($rrdFile, array(config('rrd.ds'), "--resolution", config("rrd.step"), "--start", (time() - 86400), "--end", (time() - 350)));
            if ($result != false) {
                foreach ($result["data"] as $key => $value) {
                    $labels = array();
                    foreach ($value as $time => $row) {
                        $set = 0;
                        if (is_finite($row)) {
                            $array[$key][] = $row;
                        } else {
                            $array[$key][] = 0;
                        }
                        $labels[] = $time;
                    }
                }

                foreach ($labels as $value) {
                    $formatted_timestamps[] = date("F-j-Y g:i a", $value);
                }
                $count = 0;
                foreach ($array as $key => $values) {
                    ${$key . "station_chart"} = new \App\Charts\LineChart();
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
            }
        }
            return view('device.intracom.station', compact('charts', 'statable'));
        }

    public function showDeviceAjax($id){
        $array = array();
        $date = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $device = Device::find($id);
        if($device->devicetype_id ="29"){
            $qam = array(
                "1" => "4-QAM-L",
                "2" => "4-QAM-M",
                "3" => "4-QAM-H",
                "4" => "16-QAM-L",
                "5" => "16-QAM-H",
                "6" => "64-QAM",
                "7" => "128-QAM",
                "8" => "265-QAM",
                "9" => "512-QAM",
                "10" => "1024-QAM-L",
                "11" => "1024-QAM-H"
            );
        }
        $statables = Statable::with('device')->where('device_id',$id)->get();
        foreach ($statables as $statable){
            if ($statable->updated_at>$formatted_date){
                $connected = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green"></i>';
            }else{
                $connected ="";
            }
            if ($statable->rxsignal<0){
                $connected = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green"></i>';
            }else{
                $connected ="";
            }
            if(array_key_exists($statable->rx_rate,$qam)){
                $rxqam = $qam[$statable->rx_rate];
            }else{
                $rxqam = "n/a";
            }
            if(array_key_exists($statable->tx_rate,$qam)){
                $txqam = $qam[$statable->tx_rate];
            }else{
                $txqam = "n/a";
            }
            $array[] = array(
                "<a href='/statables/pergraph/".$statable->id."'>".$statable->mac."</a>",
                $statable->ip,
                $statable->distance." km",
                ($statable->rxsignal/100),
                ($statable->txsignal/100),
                ($statable->rx_snr/100),
                ($statable->tx_snr/100),
                round(($statable->rx_snr/100)+($statable->rxsignal/100),2),
                round(($statable->tx_snr/100)+($statable->txsignal/100),2),
                $rxqam,
                $txqam,
                $statable->rx_utilization/100,
                round($statable->rx_max_utilization/1000000,2),
                round($statable->tx_utilization/100,2),
                round($statable->tx_max_utilization/1000000,2),
                $statable->time,
                $connected
            );
        }
        return $array;
    }
}
