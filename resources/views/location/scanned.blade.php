@extends('layouts.app')

@section('title', 'Location')

@section('content')
    @push('head')
        <style>
            select option[value="25"] {
                background: rgba(241, 169, 160, 1);
            }

        </style>
    @endpush
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Scan Location</div>

                <div class="panel-body">

                    {!! Form::open(['url'=>"/locations/addscan/$location->id"]) !!}

                    @foreach($ips['address'] as $key=> $ip)
                        @if(($ips['exists'][$key]==1))
                        <div class="panel panel-default alert alert-danger">
                            <div class="panel-heading bg-danger">Scan Location</div>
                            <div class="panel-body  alert alert-danger">
                                    <p>Device Already Exists!!</p>
                                    <div class="form-group  alert alert-danger" id="{!! $ip !!}">
                                    {{ Form::label('IP to Add', null, ['class' => 'control-label']) }}
                                    {{ Form::text("$key"."ip", $ip, ['class' => 'form-control']) }}
                                    {{ Form::label('Device Name', null, ['class' => 'control-label']) }}
                                    {{ Form::text($key."name", null, ['class' => 'form-control']) }}
                                    {{ Form::label('Device type ', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."devicetype_id", $devicetypes, $ips["vendor"][$key], ['class' => 'js-example-basic-single form-control','id'=>'devicetype_id']) }}
                                    {{ Form::label('Add this Device?', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."add", array('Y' => 'Yes', 'N' => 'No'),'N', ['class' => 'form-control']) }}
                                    </div>
                            </div>
                        </div>
                        @else
                        <div class="panel panel-default">
                            @if($ips["vendor"][$key]=="25")
                                <p>Device Type not identified !!</p>
                                <div class="form-group  alert alert-danger" id="{!! $ip !!}">
                                    {{ Form::label('IP to Add', null, ['class' => 'control-label']) }}
                                    {{ Form::text("$key"."ip", $ip, ['class' => 'form-control']) }}
                                    {{ Form::label('Device Name', null, ['class' => 'control-label']) }}
                                    {{ Form::text($key."name", null, ['class' => 'form-control']) }}
                                    {{ Form::label('Device type ', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."devicetype_id", $devicetypes, $ips["vendor"][$key], ['class' => 'js-example-basic-single form-control','id'=>'devicetype_id']) }}
                                    {{ Form::label('Add this Device?', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."add", array('Y' => 'Yes', 'N' => 'No'),'N', ['class' => 'form-control']) }}
                                </div>
                            @else
                            <div class="panel-heading">Scan Location</div>
                            <div class="panel-body">
                                <div class="form-group" id="{!! $ip !!}">
                                    {{ Form::label('IP to Add', null, ['class' => 'control-label']) }}
                                    {{ Form::text("$key"."ip", $ip, ['class' => 'form-control']) }}
                                    {{ Form::label('Device Name', null, ['class' => 'control-label']) }}
                                    {{ Form::text($key."name", null, ['class' => 'form-control']) }}
                                    {{ Form::label('Device type ', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."devicetype_id", $devicetypes, $ips["vendor"][$key], ['class' => 'js-example-basic-single form-control','id'=>'devicetype_id']) }}
                                    {{ Form::label('Add this Device?', null, ['class' => 'control-label']) }}
                                    {{ Form::select($key."add", array('Y' => 'Yes', 'N' => 'No'), ['class' => 'form-control']) }}
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                    @endforeach

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
