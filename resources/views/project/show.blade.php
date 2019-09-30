@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading" ><strong>{{$project->name}}</strong></div>

                <div class="panel-body">
                    <h3>Tasks in this project</h3>
                    <table class="table table-hover">
                        <thead>
                        <th>Name</th>
                        <th>Descripton</th>
                        <th>Project</th>
                        <th>Completed</th>
                        <th>User</th>
                        <th>Re Asign</th>
                        <th>Comment</th>

                        </thead>
                        <tbody>
                        @foreach ($project->tasks as $task)
                            <tr>
                                <td>{{$task->name}}</td>
                                <td>{{$task->description}}</td>
                                <td>{{$task->project->name}}</td>
                                <td>
                                    @if ($task->completed == "1")
                                        <a class="fa fa-check-circle" aria-hidden="true" style="color:green" href="{{ route('task.complete',$task->id) }}"></a>
                                    @else
                                        <a class="fa fa-times-circle-o" aria-hidden="true" style="color:red" href="{{ route('task.complete',$task->id) }}"></a>
                                    @endif
                                </td>
                                {{----}}
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
                                    </div>
                                </td>
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
@endsection
