
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Updating {{$device->name}}</div>

                <div class="panel-body">

             
                    {!! Form::model($device,['method'=>'PATCH', 'route' => ['device.update', $device->id]]) !!}


                    @extends('layouts.app')

                    @section('content')
                        <div class="container">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Create a new device</div>

                                        <div class="panel-body">



                                            {!! Form::open(['action'=>'HighsiteformController@store']) !!}


                                            <div class="form-group">
                                                {{ Form::label('Device location ', null, ['class' => 'control-label']) }}
                                                {{ Form::select('location_id', $locations) }}
                                            </div>

                                            <div class="form-group" style="visibility: hidden">
                                                {{ Form::text('noc_user_id',Auth::user()->id) }}
                                            </div>
                                            @if
                                            <div class="form-group">
                                                {{ Form::label('Field Technician ', null, ['class' => 'control-label']) }}
                                                {{ Form::select('field_user_id', $users) }}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Sector frequencies checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('sector_freq_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Spanning Tree checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('stp_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Power Monitor checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('pm_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Routing double checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('routing_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Ports marked on TS', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('routing_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Cameras live', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('cameras_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Users with bad ccq ', null, ['class' => 'control-label']) }}
                                                {{ Form::text('name','comma seperated list') }}
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

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
