<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Input;


class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('task.index',compact('tasks'));
    }

    public function viewMyTasks()
    {
        $tasks = Task::where('user_id',\Auth::user()->id)->get();
        return view('task.index',compact('tasks'));
    }

    public function viewUnasigned()
    {
        $tasks = Task::where('user_id',"50")->get();
        return view('task.index',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::pluck('name','id')->all();
        $users = User::pluck('name','id');

        return view('task.create',compact('projects','users'));
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
        Task::create($input);
        $tasks = Task::where('user_id',\Auth::user()->id)->get();

        return view('task.index',compact('tasks'));
    }

    public function complete($id)
    {
        $task = Task::find($id);
        if ($task->completed == "1"){
            $task->completed = "0";
        }else{
            $task->completed = "1";
        }
        $task->save();
        $project = Project::find($task->project_id);
        return redirect("project/$project->id")->with('project');

    }

    public function reasign($id){
        $task = Task::find($id);
        $users = User::pluck('name','id');

        return view('task.reasign',compact('task','users'));
    }

    public function storeReassignment(Request $request, $id){
        $task = Task::find($id);
        $input = Input::all();
        $task->user_id = $input['user_id'];
        $task->save();
        $project = Project::find($task->project_id);
        return redirect("project/$project->id")->with('project');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        return view('task.show',compact('task'));
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
    public function destroy($id)
    {
        //
    }
}
