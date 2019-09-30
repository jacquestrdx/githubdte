<?php

namespace App\Http\Controllers;

use App\Client;
use App\Notification;
use App\ClientPingWorker;
use App\Devicetype;
use App\Location;
use App\Jacques\InfluxLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('client.index');
    }

    public function ajaxAll(){
        $clients = Client::with('location')->with('devicetype')->get();

        foreach($clients as $client){
            $edit = "<a href='/client/$client->id/edit'>Edit</a>";
            $locationlink =  "<a href='/location/$client->location_id'>".$client->location->name."</a>";
            if ($client->ping == "1"){
                $ping = "<p style='color:green';>"."Online"."</p>";
            }else{
                $ping = "<p style='color:red';>"."Offline"."</p>";
            }

            if($client->radius_cap > 0) {
                if($client->radius_used > $client->radius_cap){
                        $captotal = "<p style='color:red'>".round(($client->radius_cap / 1024), 2) . " GB</p>";
                    }else{
                        $captotal = "<p style='color:green'>".round(($client->radius_cap / 1024), 2) . " GB</p>";
                    }
            }else{
                $captotal = "<p style='color:green'>Uncapped</p>";
            }

            if($client->radius_used > $client->radius_cap){
                $capused = "<p style='color:red'>".round(($client->radius_usage/1024),2)." GB</p>";
            }else{
                $capused = "<p style='color:green'>".round(($client->radius_usage/1024),2)." GB</p>";
            }
            $userlink = "<a href='/client/$client->id'>".$client->username."</a>";



            $array[] = [
                $client->id,$userlink,$client->name,$client->ip,
                $client->reseller,$capused,$captotal,$locationlink,
                $client->devicetype->name,$client->comment,$ping,$edit
            ];
        }
        return $array;
    }

    public function showOfflineVipClients(){
        $clients = Client::where('ping','0')->get();
        return view('client.vip',compact('clients'));
    }

    public function showOnlineVipClients(){
        $clients = Client::where('ping','1')->get();
        return view('client.vip',compact('clients'));
    }
    public function showAllVipClients(){
        $clients = Client::get();
        return view('client.vip',compact('clients'));
    }

    public function getClientPingsAJAX($id){
            $client = Client::find($id);

            $influx = new InfluxLibrary();
            $client->ip = trim($client->ip);
            $query = "SELECT * FROM clientpings where host ='".$client->ip."' order by time desc limit 1000 ";
            $stats = $influx->selectFromDb($query);
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $date = preg_split("/\T/", $stat->time);
                    $time = preg_split("/\./", $date['1']);
                    $time = preg_split("/\:/",$time['0']);
                    $hour = ($time['0']+ 2);
                    $minutes = $time['1'];
                    $seconds = $time['2'];
                    if ($hour < 10){
                        $hour = "0".$hour;
                    }
                    $time = $hour.":".$minutes;
                    $newtime = $date['0'] . " " . $time;
                    $stat->time = $newtime;
                    $array[] = array(
                        "year" => $stat->time,
                        "value" => $stat->value
                    );
                }
            }
            echo json_encode($array);
        }
        public function notification_log()
        {
            $date = new \DateTime;
            $date->modify('-2 days');
            $formatted_date = $date->format('Y-m-d H:i:s');
            $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('client_id','!=',"0")->orderby('updated_at', 'desc')->get();
            return view('client.notificationlog', compact('notifications'));

        }


        public function getClientTrafficAJAX($id)
        {
            $client=Client::find($id);
            $influxclient = new \crodas\InfluxPHP\Client(
                "localhost" /*default*/,
                8086 /* default */,
                "root" /* by default */,
                "root" /* by default */
            );
            $db = $influxclient->dte;
            $query = "SELECT * FROM clientinterfaces where host ='".$client->id."' order by time desc limit 2000";
            $stats = $db->query($query);
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $date = preg_split("/\T/", $stat->time);
                    $time = preg_split("/\./", $date['1']);
                    $time = preg_split("/\:/", $time['0']);
                    $hour = ($time['0'] + 2);
                    $minutes = $time['1'];
                    $seconds = $time['2'];
                    if ($hour < 10) {
                        $hour = "0" . $hour;
                    }
                    $time = $hour . ":" . $minutes;
                    $newtime = $date['0'] . " " . $time;
                    $stat->time = $newtime;
                    $array[] = array(
                        "host" => $stat->host,
                        "iname" => $stat->iname,
                        "time" => $stat->time,
                        "rxvalue" => $stat->rxvalue,
                        "txvalue" => $stat->txvalue
                    );
                }
                return $array;
            }else{
                return;
            }
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::lists('name','id');
        $devicetypes = Devicetype::lists('name','id');
        return view('client.create',compact('locations','devicetypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        $client = Client::create($input);
        $client->reseller = strtoupper($client->reseller);
        $client->save();
        $clients = Client::get();
        return redirect("client");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return view('client.show',compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        $locations = Location::lists('name','id');
        $devicetypes = Devicetype::lists('name','id');
        return view('client.edit',compact('locations','devicetypes','client'));
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
        $input = Input::all();
        Client::find($id)->update($input);
        $client = Client::find($id);
        $client->reseller = strtoupper($client->reseller);
        $client->save();
        $clients = Client::get();
        return redirect("client");
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
