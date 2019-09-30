<?php

namespace App\Http\Controllers;

use App\Acknowledgedlist;
use App\Sipaccount;
use App\Sipserver;
use Illuminate\Http\Request;

use App\Http\Requests;

class SipaccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sipaccounts=Sipaccount::get();

        return view('sipaccount.index', compact('sipaccounts'));
    }

    public function onlinesips($id)
    {
        $sipaccounts=Sipaccount::where('sipserver_id','=',$id)->get();
        $topsipservername=Sipserver::where('id','=',$id)->first();

        return view('sipaccount.onlinesips', compact('sipaccounts'), compact('topsipservername'));
    }

    public function offlinesips($id)
    {
        $sipaccounts=Sipaccount::where('sipserver_id','=',$id)->get();
        $topsipservername=Sipserver::where('id','=',$id)->first();

        return view('sipaccount.offlinesips', compact('sipaccounts'), compact('topsipservername'));
    }

    public function acksips($id)
    {
        $sipaccounts=Sipaccount::where('sipserver_id','=',$id)->get();
        $topsipservername=Sipserver::where('id','=',$id)->first();

        return view('sipaccount.acksips', compact('sipaccounts'), compact('topsipservername'));
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
        $sipaccount=Sipaccount::find($id);


        return view('sipaccount.show', compact('sipaccount'));
    }

    public function configureupstream()
    {
        $sipaccounts=Sipaccount::get();
        $sipservers=Sipserver::get();
//dd($sipaccounts);
        return view('sipaccount.configureupstream', compact('sipaccounts'), compact('sipservers'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function addAcknowledge($user_id,$sip_id)
    {
//        dd($user_id,$sip_id);

        $sipaccount = Sipaccount::find($sip_id);
        if ($sipaccount->status_id == 2 )
        {
            $newacknowledgedlist = new Acknowledgedlist();
            $newacknowledgedlist->sipaccount_id = $sip_id;
            $newacknowledgedlist->user_id = $user_id;
//            dd($newacknowledgedlist);
            $newacknowledgedlist->save();

            $affectedRows = Sipaccount::find($sip_id);
            $affectedRows->ack = 1;
            $affectedRows->status_id = 3;
            $affectedRows->save();
        }


        $sipaccounts=Sipaccount::get();

        return \Redirect::back()->with('addmsg','Operation Successful !');
    }

    public function addupstreamtrunk($user_id,$sip_id)
    {
//        dd($user_id,$sip_id);

        $sipaccount = Sipaccount::find($sip_id);
        $sipaccount->upstreamTrunk = 1;
        $sipaccount->save();

        $sipaccounts=Sipaccount::get();

        return \Redirect::back()->with('addmsg','Operation Successful !');
    }

    public function removeupstreamtrunk($user_id,$sip_id)
    {
//        dd($user_id,$sip_id);

        $sipaccount = Sipaccount::find($sip_id);
        $sipaccount->upstreamTrunk = 0;
        $sipaccount->save();

        $sipaccounts=Sipaccount::get();

        return \Redirect::back()->with('addmsg','Operation Successful !');
    }



    public function destroy($id)
    {
//        dd($id);
//        $affectedRows = Sipaccount::where('id', '=', $id)->delete();
//        $affectedRows = Sipaccount::find($id)->first();
        $affectedRows = Sipaccount::find($id);
        try{
            $affectedRows->delete();
            $deletemsg = "Sip account ".$affectedRows->shortnumber." has been deleted";
            $sipaccounts=Sipaccount::get();
            echo  "
            <script>
            alert($deletemsg)
            </script>";
            return redirect("sipaccount")->with('deletemsg');
        }catch (\Exception $e){
            $sipaccounts=Sipaccount::get();
            return redirect("sipaccount")->with('sipaccounts');
            }
        }
//        return view('pages.sipaccount.index', compact('sipaccounts'));
//        return \Redirect::to('index')->with('deletemsg','Operation Successful !');
}
