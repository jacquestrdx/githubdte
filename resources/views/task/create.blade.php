@extends('layouts.app')

@section('title', 'Create Device')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create task</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'TaskController@store']) !!}

                        <div class="form-group">
                            {{ Form::label('Task name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Task Description', null, ['class' => 'control-label']) }}
                            {{ Form::text('description', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Project ', null, ['class' => 'control-label']) }}
                            {{ Form::select('project_id', $projects, null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('User ', null, ['class' => 'control-label']) }}
                            {{ Form::select('user_id', $users, null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Notes', null, ['class' => 'control-label']) }}
                            {{ Form::text('comment', null, ['class' => 'form-control']) }}
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
