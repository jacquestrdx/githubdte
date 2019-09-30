<?php

namespace App\Http\Controllers;

use App\Acknowledgement;
use App\BGPPeer;
use App\Blackboardalert;
use App\HistoricalAcknowledgement;
use App\Notification;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Device;
use App\Fault;
use App\Http\Requests;

class AcknowledgementController extends Controller
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

    public function addBlackboard($id){
        $blackboard_alert = Blackboardalert::find($id);
        return view('acknowledge.add_blackboard_alert',compact('blackboard_alert'));
    }

    public function editBlackboard($id){
        $blackboard_alert = Blackboardalert::find($id);
        return view('acknowledge.edit_blackboard_alert',compact('blackboard_alert'));
    }

    public function addBlackboardAcknowledgement($id){
        $input = Input::all();

        $blackboard_alert = Blackboardalert::find($id);
        $blackboard_alert->acknowledged = "1";
        $blackboard_alert->save();

        $acknowledgement = new Acknowledgement();
        $acknowledgement->active = "1";
        $acknowledgement->ack_note   = $input['ack_note'];
        $acknowledgement->blackboard_id  = $blackboard_alert->id;
        $acknowledgement->user_id    = \Auth::user()->id;
        $acknowledgement->save();
        $acknowledgements = Acknowledgement::where('active',"1")->get();
    }

    public function addDeviceAcknowledgement($id){
        $input = Input::all();

        $device = Device::find($id);
        $device->acknowledged = "1";
        $device->save();

        $acknowledgement = new Acknowledgement();
        $acknowledgement->active = "1";
        $acknowledgement->ack_note   = $input['ack_note'];
        $acknowledgement->device_id  = $device->id;
        $acknowledgement->user_id    = \Auth::user()->id;
        $acknowledgement->save();
        $acknowledgements = Acknowledgement::where('active',"1")->get();

        $hisacknowledgement = new HistoricalAcknowledgement();
        $hisacknowledgement->ack_note   = $input['ack_note'];
        $hisacknowledgement->device_id  = $device->id;
        $hisacknowledgement->user_id    = \Auth::user()->id;
        $hisacknowledgement->save();

        return view('acknowledge.index',compact('acknowledgements'));
    }

    public function addLocationAcknowledgement($id){
        $input = Input::all();
        $location = Location::find($id);
        $location->acknowledged = "1";
        $location->save();
        $acknowledgement = new Acknowledgement();
        $acknowledgement->ack_note   = $input['ack_note'];
        $acknowledgement->location_id  = $location->id;
        $acknowledgement->active  = 1;
        $acknowledgement->user_id    = \Auth::user()->id;
        $acknowledgement->save();

        $hisacknowledgement = new HistoricalAcknowledgement();
        $hisacknowledgement->ack_note   = $input['ack_note'];
        $hisacknowledgement->location_id  = $location->id;
        $hisacknowledgement->user_id    = \Auth::user()->id;
        $hisacknowledgement->save();

        $acknowledgements = Acknowledgement::where('active',"1")->get();
        return redirect("/home");
    }


    public function addBGPPeerAcknowledgement($id){
        $input = Input::all();

        $bgppeer = BGPPeer::find($id);
        $bgppeer->acknowledged = "1";
        $bgppeer->save();

        $acknowledgement = new Acknowledgement();
        $acknowledgement->active = "1";
        $acknowledgement->ack_note   = $input['ack_note'];
        $acknowledgement->bgppeer_id  = $bgppeer->id;
        $acknowledgement->user_id    = \Auth::user()->id;
        $acknowledgement->save();

        $hisacknowledgement = new HistoricalAcknowledgement();
        $hisacknowledgement->ack_note   = $input['ack_note'];
        $hisacknowledgement->bgppeer_id  = $bgppeer->id;
        $hisacknowledgement->user_id    = \Auth::user()->id;
        $hisacknowledgement->save();

        $acknowledgements = Acknowledgement::where('active',"1")->get();
        return redirect()->route('device.showDownBgpPeers');
    }

    public function addFaultAcknowledgement($id){
        $input = Input::all();

        $fault = Fault::find($id);
        $fault->acknowledged = "1";
        $fault->save();

        $acknowledgement = new Acknowledgement();
        $acknowledgement->active = "1";
        $acknowledgement->ack_note   = $input['ack_note'];
        $acknowledgement->fault_id  = $fault->id;
        $acknowledgement->user_id    = \Auth::user()->id;
        $acknowledgement->save();

        $hisacknowledgement = new HistoricalAcknowledgement();
        $hisacknowledgement->ack_note   = $input['ack_note'];
        $hisacknowledgement->fault_id  = $fault->id;
        $hisacknowledgement->user_id    = \Auth::user()->id;
        $hisacknowledgement->save();

        $acknowledgements = Acknowledgement::where('active',"1")->get();
        return redirect()->route('faultreport');
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
        $acknowledgement = Acknowledgement::find($id);
        return view('acknowledge.change', compact('acknowledgement'));
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
        $acknowledgement = Acknowledgement::find($input['id']);
        $acknowledgement->user_id = $input['ack_user_id'];
        $acknowledgement->ack_note = $input['ack_note'];
        //$acknowledgement = date("Y-m-d h:m:i");
        $acknowledgement->save();


        $hisacknowledgement = new HistoricalAcknowledgement();
        $hisacknowledgement->ack_note   = $input['ack_note'];
        $hisacknowledgement->fault_id  = $acknowledgement->fault_id;
        $hisacknowledgement->device_id  = $acknowledgement->device_id;
        $hisacknowledgement->location_id  = $acknowledgement->location_id;
        $hisacknowledgement->user_id    = $acknowledgement->user_id;
        $hisacknowledgement->save();

        return redirect("/home");
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
