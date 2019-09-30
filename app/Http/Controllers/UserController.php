<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\InterfaceWarning;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\UserNotification;
use App\Jacques\MikrotikLibrary;

use App\Http\Requests;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->user_type == "admin"){
            $users = User::get();
            return view('user.index', compact('users'));
        }else {
            return view('prohibited');
        }
    }

    public function getAllAJAX(){
        $users = User::get();
        foreach($users as $user){
            $array[$user->id] = $user->name;
        }

        return $array;
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

    public function showInbox(){
        return view ('user.inbox');
    }

    public function ajaxInbox($id){
        $user = User::find($id);
        $notifications = UserNotification::where('user_id',$user->id)->where('interfacewarning_id','!=','0')->get();
        foreach ($notifications as $notification){
            $deviceid = $notification->interfacewarning->dinterface->device_id;
            $devicename = $notification->interfacewarning->dinterface->device->name;
            $interfacename = $notification->interfacewarning->dinterface->name;
            $warningid = $notification->interfacewarning->id;
            $acknowledge = "<a href='/inbox/acknowledgeinterfacewarning/$warningid'>Acknowledge</a>";
            $device = "<a href='/device/$deviceid'>$devicename</a>";
            $dinterfaceid=$notification->interfacewarning->dinterface_id;
            $interface = "<a href='/dinterface/$dinterfaceid'>$interfacename</a>";
            $array[] = [$device,$interface,$notification->interfacewarning->message,$notification->interfacewarning->time,$acknowledge];
        }
        return $array;
    }

    public function verifyUser($id){
        if (\Auth::user()->user_type=="admin"){
            $user = User::find($id);
            $user->verified = 1;
            $user->save();
            \Session::flash('status', 'User successfully edited!');
            \Session::flash('notification_type', 'Success');
            return redirect("user");
        }else{
            \Session::flash('status', 'You do not have sufficient rights!!!');
            \Session::flash('notification_type', 'Error');
            return redirect("user");
        }
    }
    public function unVerifyUser($id){
        if (\Auth::user()->user_type=="admin") {
            $user = User::find($id);
            $user->verified = 0;
            $user->save();
            \Session::flash('status', 'User successfully edited!');
            \Session::flash('notification_type', 'Success');
            return redirect("user");
        }else{
            \Session::flash('status', 'You do not have sufficient rights!!!');
            \Session::flash('notification_type', 'Error');
            return redirect("user");
        }
    }

    public function acknowledgeInterfaceWarning($id){
        $interfacewarning = InterfaceWarning::find($id);
        $interfacewarning->delete();
        $usernotifications = UserNotification::where('interfacewarning_id','!=','0')->where('interfacewarning_id',$id)->get();
        foreach ($usernotifications as $usernotification){
            $usernotification->delete();
        }
        return redirect("inbox");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        MikrotikLibrary::findPeakInterfaces();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit',compact('user'));
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
        if (\Auth::user()->user_type=="admin") {

            $user = User::find($id);
            $input = Input::all();
            if ($input['user_type'] == "1") {
                $user->user_type = "admin";
            }
            if ($input['user_type'] == "0") {
                $user->user_type = "CC";
            }

            if(array_key_exists('receive_reports',$input)){
                $user->receive_reports = 1;
                $user->save();
            }else {
                $user->receive_reports = 0;
                $user->save();
            }
            if(array_key_exists('receive_notifications',$input)){
                $user->receive_notifications = 1;
                $user->save();
            }else {
                $user->receive_notifications = 0;
                $user->save();
            }
            $user->save();
            $users = User::get();
        }
        return redirect("user")->with('users');

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
