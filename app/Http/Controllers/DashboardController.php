<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use App\Dashboard;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dashboards = Dashboard::get();
        return view('dashboard.custom.index', compact('dashboards','sipservers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.custom.create');
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
        Dashboard::create($input);
        return redirect('customdash');
    }

    public function addItem($id){
        $dashboard = Dashboard::find($id);
        $locations = Location::orderBy('name','asc')->lists('name','id');
        return view('dashboard.custom.additem',compact('dashboard','locations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dashboard = Dashboard::find($id);
        return view('dashboard.custom.show',compact('dashboard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dashboard = Dashboard::find($id);

        return view('dashboard.custom.edit');
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
        //
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

    public function refreshDashboard(){

        /*$sipaccounts = DB::table('sipaccounts')->get();*/
        $sipaccounts = Sipaccount::all();
        $sipservers = Sipserver::get();

        return view('layouts.refreshlayouts.dashboardrefresh', compact('sipaccounts','sipservers'));
    }


}
