@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Tasks</div>
                    <div style="margin-top: 20px;margin-left: 20px;" class="panel-default">
                        <a href="{{ url('/task/') }}"  class="btn btn-default">All tasks</a>
                        <a href="{{ url('/task/filter/unassigned/') }}"  class="btn btn-default">Unasigned tasks </a>
                        <a href="{{ url('/task/filter/mytasks/') }}"  class="btn btn-default">Your tasks </a>
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">                            <thead>
                            <th>Name</th>
                            <th>Descripton</th>
                            <th>Project</th>
                            <th>Due Date</th>
                            <th>Completed</th>
                            <th>User</th>
                            <th>Re Asign</th>
                            <th>Notes</th>

                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{$task->name}}</td>
                                    <td>{{$task->description}}</td>
                                    <td>{{$task->project->name}}</td>
                                    <td>{{$task->due_date}}</td>
                                    <td>
                                            @if ($task->completed == "1")
                                                <a class="fa fa-check-circle" aria-hidden="true" style="color:green" href="{{ route('task.complete',$task->id) }}"></a>
                                            @else
                                                <a class="fa fa-times-circle-o" aria-hidden="true" style="color:red" href="{{ route('task.complete',$task->id) }}"></a>
                                            @endif
                                    </td>
                                    <td>{{$task->user->name}}</td>
                                    <td>
                                            <a href="{{ route('task.reasign',$task->id) }}">Re Asign</a>
                                    </td>
                                    <td>
                                        <div id="popup">
                                            <a href="{{ route('add.comment',$task->id) }}">
                                            <span>
                                                @foreach($task->comments as $comment)
                                                    <p>{{$comment->comment}}</p>
                                                @endforeach
                                                </span>Comment</a>
                                        </div></td>
                                </tr>
                            @endforeach
                            </tbody>
                            {{----}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
