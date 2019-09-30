@extends('layouts.app')

@section('title', 'Add Reason')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Why is this client offline?</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>['PppoeclientController@storeReason',$pppoe->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Why is the client offline', null, ['class' => 'control-label']) }}
                            {{ Form::text('reason', null, ['class' => 'form-control']) }}
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
