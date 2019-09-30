@extends('layouts.app')
@section('title', 'Signal Calculator')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Calculate Signal</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'DeviceController@calculateSignal']) !!}

                        <div class="form-group">
                            {{ Form::label('Noise Floor ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('noisefloor', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Sector Output Power ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('sectoroutput', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Sector Gain ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('sectorgain', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('CPE Gain ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('cpegain', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Distance ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('distance', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Frequency ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('freq', null, ['class' => 'form-control']) }}
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
