<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Hscontact;
use App\Location;
Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;


class hscontactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hscontacts = hscontact::get();

        return view('hscontact.index',compact('hscontacts'));
    }

    public function down()
    {
        $hscontacts = hscontact::where('ping', '!=', 1)->get();
        return view('hscontact.down',compact('hscontacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('hscontact.create', compact('hscontacts'));
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
        Hscontact::create($input);
        $hscontacts = Hscontact::get();
        return view('hscontact.index', compact('hscontacts'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hscontact=Hscontact::find($id);

        return view('hscontact.show',compact('hscontact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit($id){
        $hscontact = hscontact::find($id);
        $locations = Location::lists('name','id');
        return view('hscontact.edit', compact('hscontact','hscontacts','locations','selectedlocation','hscontacttypes','selectedtype'));
}
    
    public function update($id)
    {
        $input = Input::all();
        hscontact::find($id)->update($input);
        $hscontact = hscontact::find($id);
        //return Redirect::back()->with('message','hscontact updated.');
        return view('hscontact.show',compact('hscontact'));
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
