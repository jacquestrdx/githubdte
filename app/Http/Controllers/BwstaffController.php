<?php

namespace App\Http\Controllers;

use App\Bwstaff;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class bwstaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bwstaffs = bwstaff::get();

        return view('bwstaff.index',compact('bwstaffs'));
    }

    public function down()
    {
        $bwstaffs = bwstaff::where('ping', '!=', 1)->get();
        return view('bwstaff.down',compact('bwstaffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $bwstaffs = bwstaff::get();
        $locations = Location::lists('name','id');

        return view('bwstaff.create', compact('bwstaffs'));
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
        bwstaff::create($input);
        $bwstaff_id = $input["id"];
        $bwstaff=Bwstaff::find($bwstaff_id);

        return view('location.show', compact('bwstaff'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bwstaff=bwstaff::find($id);

        return view('bwstaff.show',compact('bwstaff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit($id){
        $bwstaff = bwstaff::find($id);
        $locations = Location::lists('name','id');
        return view('bwstaff.edit', compact('bwstaff','bwstaffs','locations','selectedlocation','bwstafftypes','selectedtype'));
}
    
    public function update($id)
    {
        $input = Input::all();
        bwstaff::find($id)->update($input);
        $bwstaff = bwstaff::find($id);
        //return Redirect::back()->with('message','bwstaff updated.');
        return view('bwstaff.show',compact('bwstaff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
