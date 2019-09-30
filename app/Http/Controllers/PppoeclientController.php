<?php

namespace App\Http\Controllers;

use App\Device;
use App\Statable;
use App\Jacques\InfluxLibrary;
use App\Location;
use App\Devicetype;
use App\Notification;
use App\Jacques\RouterosAPI;
use App\Acknowledgement;
use App\Pppoeclient;
use App\StationProblem;
use Illuminate\Http\Request;
use App\Highsiteform;
use App\DeviceUpdateController;
use Illuminate\Support\Facades\Input;
use App\Fault;
use App\Jacques\SmtpLibrary;
use App\SlaReport;


class PppoeclientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pppoeclient.index');
    }

    public function showOffline(){
        return view('pppoeclient.offline');
    }

    public function showthismonth(){
        return view('pppoeclient.thismonth');
    }

    public function showallAJAX(){
    $array = array();
    $pppoes     = Pppoeclient::with('device')->with('statable')->get();
        foreach ($pppoes as $pppoe){
            if ($pppoe->statable_id !=0){
                $stationssid = $pppoe->statable->device->ssid;
                $sectorid = $pppoe->statable->device->id;
            }else{
                $stationssid = "No station";
                $sectorid = "0";
            }
            if (isset($pppoe->device->name)){
                $devicename = $pppoe->device->name;
            }else{
                $devicename = "Device has been deleted";
            }
            $array[] = array(
                $pppoe->id,
                $pppoe->username,
                $pppoe->ip,
                $pppoe->mac,
                $pppoe->vendor,
                $pppoe->device->name,
                "<a href='/device/$sectorid'>$stationssid</a>",
                $pppoe->is_online,
                date_format($pppoe->updated_at,"Y/m/d H:i:s"),
                "<a href='/pppoeclient/addreason/$pppoe->id'>$pppoe->reason - Add</a>",

            );
        }
        return $array;
    }

    public function SectorAJAX($id){
        $array = array();
        $statables = Statable::where('device_id',$id)->lists('id');
        $pppoes     = Pppoeclient::with('device')->with('statable')->wherein('statable_id',$statables)->get();
        $downsum = 0;
        $upsum = 0;
        foreach ($pppoes as $pppoe){
            try{
            if($pppoe->statable->device_id == $id){
                $downsum += round(($pppoe->download_speed / 1024 / 1024),2);
                $upsum += round(($pppoe->upload_speed / 1024 / 1024),2);
                if($pppoe->download_speed=="1"){
                    $download = "Cancelled";
                }else{
                    $download = ($pppoe->download_speed/1024/1024);
                }
                if($pppoe->upload_speed=="1"){
                    $upload = "Cancelled";
                }else{
                    $upload = ($pppoe->upload_speed/1024/1024);
                }
                $array[] = array(
                    $pppoe->id,
                    $pppoe->username,
                    $pppoe->ip,
                    $pppoe->mac,
                    $pppoe->vendor,
                    $download,
                    $upload
                    ,
                );
            }else{

            }
            }catch(\Exception $e){

            }

        }
        $device =Device::find($id);
        $device->comment =" ".round($downsum,2)." Mbps / ".round($upsum,2)." Mbps";
        $device->save();
        return $array;
        }


    public function showofflineAJAX(){
        $array = array();
        $pppoes     = Pppoeclient::where("is_online","0")->with('device')->get();
        foreach ($pppoes as $pppoe){
            if ($pppoe->statable_id !=0){
                $stationssid = $pppoe->statable->device->ssid;
                $sectorid = $pppoe->statable->device->id;
            }else{
                $stationssid = "No station";
                $sectorid = "0";
            }
            $array[] = array(
                $pppoe->id,
                $pppoe->username,
                $pppoe->ip,
                $pppoe->mac,
                $pppoe->vendor,
                $pppoe->device->name,
                "<a href='/device/$sectorid'>$stationssid</a>",
                $pppoe->is_online,
                $pppoe->last_seen,
                "<a href='/pppoeclient/addreason/$pppoe->id'>$pppoe->reason - Add</a>",
            );
        }
        echo json_encode($array);
    }
    public function showthismonthAJAX(){
        $array = array();
        $pppoes     = Pppoeclient::where("is_online","0")->whereMonth('last_seen', '=', date('m'))->whereYear('last_seen', '=', date('Y'))->with('device')->get();
        foreach ($pppoes as $pppoe){
            if ($pppoe->statable_id !=0){
                $stationssid = $pppoe->statable->device->ssid;
                $sectorid = $pppoe->statable->device->id;
            }else{
                $stationssid = "No station";
                $sectorid = "0";
            }
            $array[] = array(
                $pppoe->id,
                $pppoe->username,
                $pppoe->ip,
                $pppoe->mac,
                $pppoe->vendor,
                $pppoe->device->name,
                "<a href='/device/$sectorid'>$stationssid</a>",
                $pppoe->is_online,
                $pppoe->last_seen,
                "<a href='/pppoeclient/addreason/$pppoe->id'>$pppoe->reason - Add</a>",
            );
        }
        echo json_encode($array);
    }

    public function storeReason($id){
        $pppoe = Pppoeclient::find($id);
        $input = Input::all();
        $pppoe->reason = $input['reason'];
        $pppoe->save();

        return redirect("pppoeclient");

    }

    public function report(){
        return view ('pppoeclient.report');
    }

    public function addReason($id){
        $pppoe = Pppoeclient::find($id);

        return view('pppoeclient.addreason',compact('pppoe'));

    }

    public function getDownPPPoeAllTime(){
        $pppoes     = Pppoeclient::where("is_online","0")->count();
        echo json_encode($pppoes);
    }

    public function getDownPPPoeThisMonth(){
        $pppoes     = Pppoeclient::where("is_online","0")->whereMonth('last_seen', '=', date('m'))->count();
        echo json_encode($pppoes);
    }

    public function getDownPPPoeNoReason(){
        $pppoes     = Pppoeclient::where("is_online","0")->whereNull('reason')->count();
        echo json_encode($pppoes);
    }




}
