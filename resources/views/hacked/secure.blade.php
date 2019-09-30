@extends('layouts.app')
@section('title', 'Secure a router')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Secure a router</div>

                    <div class="panel-body">

                        {{ Form::open(array('url' => '/devices/securerouter', 'class' => 'pull-left')) }}

                        <div class="form-group">
                            {{ Form::label('IP ', null, ['class' => 'control-label']) }} {{ Form::text('ip', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="col-md-1">
                            {!! Form::submit("Secure", ['class' => 'btn btn-default']) !!}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
