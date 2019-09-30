@extends('layouts.app')

@section('title', 'Location')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">New Location</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'LocationController@store']) !!}
                    
                    <div class="form-group">
                    {{ Form::label('Location name ', null, ['class' => 'control-label']) }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Location latitude ', null, ['class' => 'control-label']) }}
                    {{ Form::text('lat', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Location longitude ', null, ['class' => 'control-label']) }}
                    {{ Form::text('lng', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Highsite Owner', null, ['class' => 'control-label']) }}
                        {{ Form::select('hscontact_id', $hscontacts, null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Location batteries ', null, ['class' => 'control-label']) }}
                    {{ Form::text('batteries', null, ['class' => 'form-control']) }}
                    </div>  
                    
                    <div class="form-group">
                    {{ Form::label('Location Standby time (hrs)', null, ['class' => 'control-label']) }}
                    {{ Form::text('standbytime', null, ['class' => 'form-control']) }}
                    </div>  
                    

                    <div class="form-group">
                    {{ Form::label('Location images ', null, ['class' => 'control-label']) }}
                    {{ Form::file('image', null, ['class' => 'form-control']) }}
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
