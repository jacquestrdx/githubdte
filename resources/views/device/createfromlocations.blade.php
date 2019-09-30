@extends('layouts.app')

@section('title', 'Create Device')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create device</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'DeviceController@store']) !!}

                        <div class="form-group">
                            {{ Form::label('Device name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Device IP ', null, ['class' => 'control-label']) }}
                            {{ Form::text('ip', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Device type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('devicetype_id', $devicetypes, null, ['class' => 'js-example-basic-single form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Device location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('location_id', $locations, $location->id8, ['class' => 'js-example-basic-single form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Check Voltage ? ', null, ['class' => 'control-label']) }}
                            {{ Form::checkbox('voltage_monitor', '1')}}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Voltage Threshold ', null, ['class' => 'control-label']) }}
                            {{ Form::text('voltage_threshold', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Voltage Offset ', null, ['class' => 'control-label']) }}
                            {{ Form::text('voltage_offset', null, ['class' => 'form-control']) }}
                        </div>



                        <div class="form-group" style="visibility: hidden">
                            {{ Form::text('fault_description',",") }}
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
