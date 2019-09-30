<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Devicetype;

use App\Device;
use App\Location;

Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;

class DevicetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $devicetypes= Devicetype::get();
        return view('devicetype.index',compact('devicetypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('devicetype.create');
    }

    public function map($id)
    {   
        $devicetype = Devicetype::find($id);
        return view('devicetype.map',compact('devicetype'));
    }

    public function ajaxAll(){
        $devicetypes = Devicetype::get();
        $array = array();

        foreach($devicetypes as $devicetype){
            $array[] = [$devicetype->id,$devicetype->name];
        }

        return $array;
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
        Devicetype::create($input);
        $devicetypes= Devicetype::get();

        return view('devicetype.index',compact('devicetypes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $devicetype= Devicetype::find($id);
        return view('devicetype.show',compact('devicetype'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit($id)
    {
        $devicetype = Devicetype::find($id);
        return view('devicetype.edit', compact('devicetype'));
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
        Devicetype::find($id)->update($input);
        return Redirect::back()->with('message','Devicetype updated.');
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
