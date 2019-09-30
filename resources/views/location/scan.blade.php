@extends('layouts.app')

@section('title', 'Location')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Scan Location</div>
                <div class="panel-body">

                    {!! Form::open(['url'=>"/locations/doscan/$location->id"]) !!}
                    
                    <div class="form-group">
                    {{ Form::label('IP Range to scan with /subnet', null, ['class' => 'control-label']) }}
                    {{ Form::text('ip_range', "192.168.0.0/24", ['class' => 'form-control']) }}
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
