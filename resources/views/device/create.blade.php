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
                        <div class="panel panel-default">
                        <div class="panel-heading"> General Settings</div>
                            <div class="panel-body">
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
                                    {{ Form::select('devicetype_id', $devicetypes, null, ['class' => 'js-example-basic-single form-control','id'=>'devicetype_id']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Device location ', null, ['class' => 'control-label']) }}
                                    {{ Form::select('location_id', $locations, null, ['class' => 'js-example-basic-single form-control']) }}
                                </div>
                                <div id="api_port">
                                    <div class="form-group">
                                        {{ Form::label('Api Port ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('api_port', "8728", ['class' => 'form-control','id'=>'api_port_id']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('License 1 ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('license_1', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('License 2 ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('license_2', null, ['class' => 'form-control']) }}
                                </div>

                                <div>
                                    <div class="form-group">
                                        {{ Form::label('Username ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('md5_username', "", ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        {{ Form::label('Password ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('md5_password', "", ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="antenna_div" class="panel panel-default">
                            <div class="panel-heading">Wireless Antenna Settings</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('Device Antenna ', null, ['class' => 'control-label']) }}
                                        {{ Form::select('antenna_id', $antennas, null, ['class' => 'js-example-basic-single form-control']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('Antenna Tile  ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('antenna_tilt', null, ['class' => 'form-control']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('Antenna Heading ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('antenna_heading', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                        </div>
                        <div class="panel panel-default" id="voltage_div">
                            <div class="panel-heading">Voltage Settings</div>
                            <div class="panel-body">
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
                            </div>
                        </div>

                        {{--<div id="password_div" class="panel panel-default">--}}
                            {{--<div class="panel-heading">Device Password Settings</div>--}}
                            {{--<div class="panel-body">--}}
                                {{--<div class="form-group">--}}
                                    {{--{{ Form::label('Device username ', null, ['class' => 'control-label']) }}--}}
                                {{--</br>--}}
                                    {{--{{ Form::password('md5_username', null, ['class' => 'form-control']) }}--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--{{ Form::label('Device password  ', null, ['class' => 'control-label']) }}--}}
                                    {{--</br>--}}
                                    {{--{{ Form::password('md5_password', null, ['class' => 'form-control']) }}--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</div>--}}



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

@push('scripts')
        <script>

            $(document).on("change", '#devicetype_id', function(e) {

                var contents = $('#antenna_div');
                var selectedDeviceType = $("#devicetype_id option:selected").val();
                if ( (selectedDeviceType =="2") ||
                    (selectedDeviceType =="5") ||
                    (selectedDeviceType =="8") ||
                    (selectedDeviceType =="9") ||
                    (selectedDeviceType =="10") ||
                    (selectedDeviceType =="11") ||
                    (selectedDeviceType =="12") ||
                    (selectedDeviceType =="13") ||
                    (selectedDeviceType =="14") ||
                    (selectedDeviceType =="15") ||
                    (selectedDeviceType =="17") ||
                    (selectedDeviceType =="19") ||
                    (selectedDeviceType =="22") ) {
                    $('#antenna_div').show();
                } else {
                    $('#antenna_div').hide();
                }
                if ( (selectedDeviceType =="1") ||
                    (selectedDeviceType =="15") ) {
                    $('#api_port').show();
                } else {
                    $('#api_port').hide();
                }

                var contents = $('#antenna_div');
                var selectedDeviceType = $("#devicetype_id option:selected").val();
                if ( (selectedDeviceType =="1") ||
                    (selectedDeviceType =="4") ) {
                    $('#voltage_div').show();
                } else {
                    $('#voltage_div').hide();
                }
            });

            $( document ).ready(function() {
                var contents = $('#antenna_div');
                var selectedDeviceType = $("#devicetype_id option:selected").val();
                if ( (selectedDeviceType =="2") ||
                    (selectedDeviceType =="5") ||
                    (selectedDeviceType =="8") ||
                    (selectedDeviceType =="9") ||
                    (selectedDeviceType =="10") ||
                    (selectedDeviceType =="11") ||
                    (selectedDeviceType =="12") ||
                    (selectedDeviceType =="13") ||
                    (selectedDeviceType =="14") ||
                    (selectedDeviceType =="15") ||
                    (selectedDeviceType =="17") ||
                    (selectedDeviceType =="19") ||
                    (selectedDeviceType =="22") ) {
                    $('#antenna_div').show();
                } else {
                    $('#antenna_div').hide();
                }
                if ( (selectedDeviceType =="1") ||
                    (selectedDeviceType =="15") ) {
                    $('#api_port').show();
                } else {
                    $('#api_port').hide();
                }

                var contents = $('#antenna_div');
                var selectedDeviceType = $("#devicetype_id option:selected").val();
                if ( (selectedDeviceType =="1") ||
                    (selectedDeviceType =="4") ) {
                    $('#voltage_div').show();
                } else {
                    $('#voltage_div').hide();
                }
            });
        </script>
@endpush