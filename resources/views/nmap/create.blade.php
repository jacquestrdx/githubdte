@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new nmap subnet</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'NmapController@store', 'class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            {{ Form::label('Description ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('description', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('subnet ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('subnet', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 1', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_1', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 2', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_2', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 3', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_3', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">

                            {{ Form::label('Port 4', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_4', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">

                            {{ Form::label('Port 5', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_5', null, ['class' => 'form-control']) }}
                            </div>
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
