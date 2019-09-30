@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')



    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$device->name}}</div>
                    <div class="panel-body">

                        {!! Form::model($device,['method'=>'POST', 'route' => ['device.updatepassword', $device->id]]) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading"> General Settings</div>
                            <div class="panel-body">
                                <div id="password_div" class="panel panel-default">
                                    <div class="panel-heading">Device Password Settings</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {{ Form::label('Device username ', null, ['class' => 'control-label']) }}
                                            </br>
                                            {{ Form::password('md5_username', null, ['class' => 'form-control','id'=>'username']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Device password  ', null, ['class' => 'control-label']) }}
                                            </br>
                                            {{ Form::password('md5_password', null, ['class' => 'form-control','id'=>'password']) }}
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
                </div>
            </div>
        </div>
    </div>



@endsection
