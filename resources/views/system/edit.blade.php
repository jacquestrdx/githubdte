@extends('layouts.app')

@section('title', 'Edit  system preferences')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating system preferences</div>
                    <div class="panel-body">

                        {!! Form::model($system,['method'=>'PATCH', 'route' => ['system.update', $system->id]]) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">Polling preferences</div>
                            <div class="panel-body">

                        <div class="form-group">
                            {{ Form::label('Map Centre Latitude ', null, ['class' => 'control-label']) }}
                            {{ Form::text('latitude', $system->latitude, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Map Centre Longitude ', null, ['class' => 'control-label']) }}
                            {{ Form::text('longitude', $system->longitude, ['class' => 'form-control']) }}
                        </div>
                            {{ Form::label('SNMP Community', null, ['class' => 'control-label']) }}
                            {{ Form::text('ubnt_snmpcommunity', $system->ubnt_snmpcommunity, ['class' => 'form-control']) }}
                        </div>
                            @if($system->include_hotspot=="1")
                                <div class="form-group">
                                    {{ Form::label('Include hotspot in Dials ?', null, ['class' => 'control-label']) }}
                                    {{ Form::checkbox('include_hotspot', 'include_hotspot', true ) }}
                                </div>
                            @else
                                <div class="form-group">
                                    {{ Form::label('Include hotspot in Dials ?', null, ['class' => 'control-label']) }}
                                    {{ Form::checkbox('include_hotspot', 'include_hotspot', false) }}
                                </div>
                            @endif



                        <div class="form-group">
                            {{ Form::label('Report Interval', null, ['class' => 'control-label']) }}
                            {{ Form::number('HourlyReportInterval', $system->HourlyReportInterval, ['class' => 'form-control']) }}
                        </div>

                    </div>
                </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">SMTP Details</div>
                            <div class="panel-body">

                            <div class="form-group">
                                {{ Form::label('Stmp IP', null, ['class' => 'control-label']) }}
                                {{ Form::text('smtp_ip', $system->smtp_ip, ['class' => 'form-control']) }}
                            </div>

                                <div class="form-group">
                                    {{ Form::label('From email', null, ['class' => 'control-label']) }}
                                    {{ Form::text('system_email_address', $system->system_email_address, ['class' => 'form-control']) }}
                                </div>
                            @if($system->smtp_use_auth == "1")
                            <div class="form-group">
                                {{ Form::label('Use Auth ?', null, ['class' => 'control-label']) }}
                                {{ Form::checkbox('smtp_use_auth', 'smtp_use_auth', true) }}
                            </div>
                            @else
                                <div class="form-group">
                                    {{ Form::label('Use Auth ?', null, ['class' => 'control-label']) }}
                                    {{ Form::checkbox('smtp_use_auth', 'smtp_use_auth', false) }}
                                </div>
                            @endif
                            <div class="form-group">
                                {{ Form::label('Stmp Port', null, ['class' => 'control-label']) }}
                                {{ Form::text('port', $system->port, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group">
                            {{ Form::label('Stmp Username', null, ['class' => 'control-label']) }}
                            {{ Form::text('smtp_username', $system->smtp_username, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('Stmp Password', null, ['class' => 'control-label']) }}
                                {{ Form::text('smtp_password', $system->smtp_password, ['class' => 'form-control']) }}
                            </div>

                            </div>
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
