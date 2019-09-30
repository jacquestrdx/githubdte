<?php

namespace App\Http\Controllers;

use App\BGPPeer;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Acknowledgement;
use App\Device;
use App\Location;
use App\Notification;
Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;
use App\DeviceUpdateController;
use App\Devicetype;
use Auth;

class UserNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function acknowledge($id)
    {
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function markAsRead($id){
        UserNotification::markAsRead($id);
        return redirect("/home");
    }

    public function markAllAsRead(){
        UserNotification::markAllAsRead();
        return redirect("/home");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function getnotificationsounds($id)
    {
        $sounds = UserNotification::where('completed','0')->where('interfacewarning_id','=','0')->where('user_id',$id)->get();
        $array = [];
        if (!empty($sounds)) {
        foreach($sounds as $sound)
            {
                $sound->completed = "1";
                $sound->save();
                $array[] = [$sound->id,$sound->notification->message];
            }
        }
        $return = $array;

        //\DB::delete('delete from usernotifications where user_id ="' . $id . '"');

        echo json_encode($return);
    }

    public function getnotificationbar(){
        return view('layouts.notificationbar');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response2
     */
    public function edit($id)
    {
        $bgppeer = BGPPeer::find($id);
        return view('bgppeers.acknowlegde',compact('bgppeer'));
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
        BGPPeer::find($id)->update($input);
        $bgppeer = BGPPeer::find($id);
        if ($bgppeer->acknowledged == "1"){
            $acknowledgement = new Acknowledgement();
            $acknowledgement->ack_note = $bgppeer->ack_note;
            $acknowledgement->device_id = $bgppeer->device_id;
            $acknowledgement->bgppeer_id = "$bgppeer->id";
            $acknowledgement->user_id = \Auth::user()->id;
            $acknowledgement->save();
        }
        //return Redirect::back()->with('message','Device updated.');
        \Session::flash('flash_message', 'Device successfully updated!');
        $devices = Device::get();
        return redirect("bgppeersoffline")->with(compact('devices'));
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
