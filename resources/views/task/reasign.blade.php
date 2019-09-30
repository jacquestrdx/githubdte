@extends('layouts.app')

@section('title', 'Create Device')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Reasign task</div>

                    <div class="panel-body">

                        {{ Form::open(['route' => ['task.storereasign',$task->id]]) }}
                        <div class="form-group">
                            {{ Form::label('User ', null, ['class' => 'control-label']) }}
                            {{ Form::select('user_id', $users, null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
