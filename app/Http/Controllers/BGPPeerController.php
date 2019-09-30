<?php

namespace App\Http\Controllers;

use App\BGPPeer;
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

class BGPPeerController extends Controller
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
        $bgppeer = BGPPeer::find($id);
        return view('acknowledge.add_bgppeer', compact('bgppeer'));
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        $affectedRows = BGPPeer::find($id);
        $device = Device::find($affectedRows->device_id);
        $affectedRows->delete();

        if (ISSET($device->fault_description)) {
            $faultdescriptions = preg_split("/,/", $device->fault_description);
            $now               = $device->lastseen;
        } else $faultdescriptions = array();
        $date = new \DateTime;
        $date->modify('-600 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $notifications  = Notification::where('updated_at', '>', $formatted_date)->where('device_id',"$device->id")->orderby('updated_at', 'desc')->get();
        $message ="Sucessfully deleted";
        return view('device.show', compact('device', 'faultdescriptions', 'now', 'formatted_date','notifications','stats','dayuptime','weekuptime','monthuptime','message'));
    }

}
