
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new device</div>

                <div class="panel-body">



                    {!! Form::open(['action'=>'ClientController@store']) !!}
                    
                    
                    <div class="form-group">
                    {{ Form::label('Client name ', null, ['class' => 'control-label']) }}
                    {{ Form::text('name') }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Client IP ', null, ['class' => 'control-label']) }}
                    {{ Form::text('ip') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Client Username', null, ['class' => 'control-label']) }}
                        {{ Form::text('username') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Client Password ', null, ['class' => 'control-label']) }}
                        {{ Form::text('password') }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Device type ', null, ['class' => 'control-label']) }}
                    {{ Form::select('devicetype_id', $devicetypes,['class'=>'js-example-basic-single form-control']) }}
                    </div>                  

                    <div class="form-group">
                    {{ Form::label('Device location ', null, ['class' => 'control-label']) }}
                    {{ Form::select('location_id', $locations,['class'=>'js-example-basic-single form-control']) }}
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
