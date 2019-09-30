@extends('layouts.app')

@section('title', 'Custom Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new Dashboard</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'DashboardController@store']) !!}

                        <div class="form-group">
                            {{ Form::label('Name ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('title', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="col-md-1 col-md-offset-3">
                            {!! Form::submit("Create", ['class' => 'btn btn-default']) !!}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
