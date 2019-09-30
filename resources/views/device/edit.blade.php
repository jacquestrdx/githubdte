@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')



    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$device->name}}</div>
                    <div class="panel-body">

                        {!! Form::model($device,['method'=>'PATCH', 'route' => ['device.update', $device->id]]) !!}
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
                                    {{ Form::select('devicetype_id', $devicetypes, $selectedtype, ['class' => 'js-example-basic-single form-control','id'=> 'devicetype_id']) }}
                                    {{--{!! Form::select('devicetype_id', $devicetypes, $selectedtype, ['class' => 'select2dropdown']) !!}--}}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Device location ', null, ['class' => 'control-label']) }}
                                    {{ Form::select('location_id', $locations, $selectedlocation, ['class' => 'js-example-basic-single form-control']) }}
                                </div>
                                <div id="api_port">
                                    <div class="form-group">
                                        {{ Form::label('Api Port ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('api_port', "8728", ['class' => 'form-control','id'=>'api_port_id']) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        {{ Form::label('Include Interfaces ', null, ['class' => 'control-label']) }}
                                        {{ Form::text('include_interfaces', "1", ['class' => 'form-control','id'=>'api_port_id']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('Override snmp Community', null, ['class' => 'control-label']) }}
                                    {{ Form::text('snmp_community', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('License 1 ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('license_1', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('License 2 ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('license_2', null, ['class' => 'form-control']) }}
                                </div>

                            </div>
                        </div>
                        <div id="antenna_div" class="panel panel-default">
                            <div class="panel-heading">Wireless Antenna Settings</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('Device Antenna ', null, ['class' => 'control-label']) }}
                                    {{ Form::select('antenna_id', $antennas, $selectedantenna, ['class' => 'js-example-basic-single form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Antenna Tilt  ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('antenna_tilt', $device->antenna_tilt, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Antenna Heading ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('antenna_heading', $device->antenna_heading, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default" id="voltage_div">
                            <div class="panel-heading">Voltage Settings</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('Check Voltage ? ', null, ['class' => 'control-label']) }}
                                    {{ Form::checkbox('voltage_monitor', '1', $voltage_monitor)}}
                                </div>


                                <div class="form-group">
                                    {{ Form::label('Voltage Threshold ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('voltage_threshold', $device->getVoltageThreshold($device->voltage_threshold), ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Voltage Offset ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('voltage_offset', $device->getVoltageThreshold($device->voltage_offset), ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>

                        {{ Form::close() }}

                        <a style="font-size:40px" href="/device/{{$device->id}}" class="fa fa-arrow-circle-left"
                           aria-hidden="true"></a>

                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endsection
