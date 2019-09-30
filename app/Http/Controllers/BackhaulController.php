<?php

namespace App\Http\Controllers;

use App\Backhaul;
use App\Device;
use App\Backhaultype;
use App\Possible_backhaul;
use App\DInterface;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BackhaulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backhaul.index');
    }
    
    public function getallAjax(){
        $backhauls = Backhaul::with('location')->get();
        foreach($backhauls as $backhaul){
            $from_location = '<a href="/location/'.$backhaul->location_id.'">'.$backhaul->location->name.'</a>';
            $to_location = '<a href="/location/'.$backhaul->to_location_id.'">'.$backhaul->getTo_location($backhaul->to_location_id).'</a>';

            if($backhaul->linked_to_interface ==1){
                $linked = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green"></i>';
            }else{
                $linked = '<i class="fa fa-times-circle" aria-hidden="true" style="color:red"></i>';
            }

            if($backhaul->priority == 0){
                $primary = '<i class="fa fa-check-circle" aria-hidden="true" style="color:green"></i>';
            }else{
                $primary = '';
            }
            $threshold = $backhaul->dinterface->threshhold ?? $threshold="";
            if(isset($backhaul->dinterface)){
                $maxtxspeed = $backhaul->dinterface->maxtxspeed;
                $maxrxspeed = $backhaul->dinterface->maxrxspeed;
                $txspeed = $backhaul->dinterface->txspeed;
                $rxspeed = $backhaul->dinterface->rxspeed;
                $interfacename = $backhaul->dinterface->name ?? $interfacename ="";
                $interfacelink = '<a href="/dinterface/'.$backhaul->dinterface->id.'">'.$interfacename.'</a>' ?? $interfacelink="";
            }else{
                $txspeed = "0";
                $rxspeed = "0";
                $interfacename = "Interface Missing";
                $interfacelink = "Interface Missing";
                $maxtxspeed = "0";
                $maxrxspeed = "0";
            }
            if (\Auth::user()->user_type=="admin"){
                            $delete = '
                            <a class="confirm" style="color:darkred;float:right" href="/backhaul/delete/'.$backhaul->id.'">Delete
                            <span class="btn btn-danger btn-sm" title="Delete">
                            <span style="color:red" class="fa fa-minus-circle "></span></span>
                            </a>
                            ';
                        $edit = '
                            <a class="confirm" href="/backhaul/'.$backhaul->id.'/edit">Edit
                            <span class="btn btn-sm" title="Delete">
                            <span class="fa fa-edit "></span></span>
                            </a>
                            ';
            }else{
                $delete = "Insufficient Rights";
                $edit = "Insufficient Rights";
            }

            $array[] = [$backhaul->id,$from_location,$to_location,$backhaul->backhaultype->name,$interfacelink,$primary,$threshold." Mbps",
                $txspeed,$rxspeed,$maxtxspeed,$maxrxspeed,date_format($backhaul->updated_at,"Y/m/d H:i:s"),$edit,$delete];
        }

        return $array;
    }



    public function down()
    {
        $backhauls = Backhaul::where('ping', '!=', 1)->get();
        return view('backhaul.down',compact('backhauls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $backhauls = Backhaul::get();
        $backhaultypes = Backhaultype::lists('name','id');
        $locations = Location::orderby('name','ASC')->lists('name','id');
        $blank = ["",""];
        return view('backhaul.create', compact('backhauls','locations','blank','backhaultypes'));
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
       if (array_key_exists('frompossible',$input)){
           $backhaul = Backhaul::create($input);
           $backhaul->linked_to_interface = 1;
           $backhaul->save();
           return redirect("backhauls/possible");
       }else{
           $backhaul = Backhaul::create($input);
           $backhaul->linked_to_interface = 1;
           $backhaul->save();
           return redirect("backhaul");
       }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location =Location::with('backhauls')->find($id);

        return view('backhaul.show',compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit($id){
        $backhaul = Backhaul::find($id);
        $locations = Location::orderby('name','ASC')->lists('name','id');
        $blank = ["",""];
        $backhaultypes = Backhaultype::lists('name','id');

    return view('backhaul.edit', compact('backhaul','blank','locations','backhaultypes'));
}
    
    public function update($id)
    {
        $input = Input::all();
        Backhaul::find($id)->update($input);
        $backhaul = Backhaul::find($id);
        //return Redirect::back()->with('message','backhaul updated.');
        return redirect("backhaul");
    }

    public function showPossibleBackhauls(){
        $possiblebackhauls = Possible_backhaul::get();
        $backhauls = Backhaul::get();

        foreach ($backhauls as $backhaul){
            foreach ($possiblebackhauls as $possiblebackhaul){
                if (($backhaul->location_id == $possiblebackhaul->from_location) AND ($backhaul->to_location_id == $possiblebackhaul->to_location)){
                    $possiblebackhaul->added_to_backhauls = 1;
                    $possiblebackhaul->save();
                }
                if (($backhaul->to_location_id == $possiblebackhaul->from_location) AND ($backhaul->location_id == $possiblebackhaul->to_location)){
                    $possiblebackhaul->added_to_backhauls = 1;
                    $possiblebackhaul->save();
                }
            }
        }
        return view('backhaul.possible',compact('possiblebackhauls'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function flagAsAdded($id){
        $possiblebackhaul = Possible_backhaul::find($id);
        $locations = Location::lists('name','id');
        $backhaultypes = Backhaultype::lists('name','id');
        $dinterfaces = DInterface::where('device_id',$possiblebackhaul->from_device_id)->lists('name','id');
        $blank = ["",""];
        return view('backhaul.createfrompossible',compact('possiblebackhaul','locations','backhaultypes','blank','dinterfaces'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \Auth::user();
        if ($user->user_type=="admin"){
            $backhaul = Backhaul::find($id);
            $deleted = \DB::delete('delete from backhauls where id = '.'"'.$id.'"');
            return redirect("backhaul");
        }else{
            return redirect("backhaul");
        }
    }
}
