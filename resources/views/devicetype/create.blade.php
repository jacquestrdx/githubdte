@extends('layouts.app')

@section('title', 'Create device type')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create device type</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'DevicetypeController@store']) !!}

                        <div class="form-group">
                            {{ Form::label('Device Type ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            @php
                                $sub_types = array("switch" => "switch","router" => "router","wireless" => "wireless","pm" => "pm");
                            @endphp
                            {{ Form::label('Device sub type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('sub_type', $sub_types , null, ['class' => 'js-example-basic-single form-control']) }}
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
