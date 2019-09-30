<?php

namespace App\Http\Controllers;

use App\Highsite_visit_category;
use App\Highsiteform;
use App\Acknowledgement;
use App\Stock;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Device;
use App\Location;
use App\Notification;
Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;
use App\DeviceUpdateController;
use App\Devicetype;
use App\Hscontact;
use App\User;
class HighsiteformController extends Controller
{
    public function index(){
        $highsiteforms = Highsiteform::get();
        $someuser = User::first();
        return view('highsiteform.index',compact('highsiteforms','someuser'));
    }

    public function show($id){
        $highsiteform = Highsiteform::find($id);
        return view('highsiteform.index',compact('highsiteform'));
    }

    public function create(){
        $locations = Location::orderby('name','asc')->lists('name','id');
        $categories = Highsite_visit_category::orderby('description','asc')->lists('description','id');
        $users = User::where('user_type','=','field')->lists('name','id');
        $obj_locations = Location::orderby('name','desc')->get();
        return view('highsiteform.create',compact('locations','users','obj_locations','categories'));
    }

    public function edit($id){
        $highsiteform = Find($id);
        return view('highsiteform.edit',compact('highsiteform'));
    }



    public function store(Request $request)
    {
        $input = Input::all();
        $highsiteform = new Highsiteform();
        $highsiteform->location_id = $input['location_id'];
        $highsiteform->ticket_nr = $input['location_id'];
        $highsiteform->user_ids = json_encode($input['user_ids']);
        $highsiteform->job_to_do = $input['job_to_do'];
        $highsiteform->job_done = $input['job_done'];
        $highsiteform->time_started = $input['time_started'];
        $highsiteform->highsite_visit_category_id = $input['highsite_visit_category_id'];
        $highsiteform->time_ended = $input['time_ended'];
        $highsiteform->notes = $input['notes'];
        $highsiteform->save();
        if( (array_key_exists('stock_used',$input)) AND (array_key_exists('stock_description',$input))){
            foreach($input['stock_used'] as $key=>$stock){
                $store_stock[] = array(
                    "serial_nr" => $stock,
                    "description" => $input['stock_description'][$key]
                );
            }

        }
        foreach($store_stock as $store){
            $stockitem = new Stock();
            $stockitem->highsiteform_id = $highsiteform->id;
            $stockitem->description = $store['description'];
            $stockitem->serial = $store['serial_nr'];
            $stockitem->save();
        }
        \Session::flash('status', 'Location form successfully added!');
        \Session::flash('notification_type', 'Success');
        $location_id = $input["location_id"];
        return redirect("location/$location_id");

    }

    public function update($id)
    {
        $input = Input::all();
        Highsiteform::find($id)->update($input);
        $highsiteform = Highsiteform::find($id);
        $location = Location::find($highsiteform->location_id);
        $highsiteforms = Highsiteform::where('location_id','=',$location->id);
        \Session::flash('status', 'Location form successfully edited!');
        \Session::flash('notification_type', 'Success');
        return redirect("location/$location->id")->with('location','devicetypes','highsiteforms');

    }

    public function complete($id){
        $highsiteform = Highsiteform::find($id);
        return view('highsiteform.complete',compact('highsiteform'));
    }
}
