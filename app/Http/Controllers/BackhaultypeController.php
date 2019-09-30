<?php

namespace App\Http\Controllers;

use App\Backhaul;
use App\Backhaultype;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Input;
use App\Comment;
use App\Task;

class BackhaultypeController extends Controller
{
    public function index()
    {
        $backhaultypes = Backhaultype::get();
        return view('backhaultype.index',compact('backhaultypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        $backhaultype = new Backhaultype();
        $backhaultype->name = $input['name'];
        $backhaultype->color = $input['color'];
        $backhaultype->save();
        return redirect("/backhaultype");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function create(){
        return view('backhaultype.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $backhaultype = Backhaultype::find($id);
        return view('backhaultype.edit',compact('backhaultype'));
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
        $backhaultype = Backhaultype::find($id);
        $backhaultype->name = $input['name'];
        $backhaultype->color = $input['color'];
        $backhaultype->save();
        return redirect("/backhaultype");
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
