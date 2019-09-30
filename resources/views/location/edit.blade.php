@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$location->name}}</div>

                    <div class="panel-body">

                        {!! Form::model($location,['method'=>'PATCH', 'route' => ['location.update', $location->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Location name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', $location->name, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Location description ', null, ['class' => 'control-label']) }}
                            {{ Form::text('description', $location->description, ['class' => 'form-control']) }}
                        </div>



                        <div class="form-group">
                            {{ Form::label('Location latitude ', null, ['class' => 'control-label']) }}
                            {{ Form::text('lat', $location->lat, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Location longitude ', null, ['class' => 'control-label']) }}
                            {{ Form::text('lng', $location->lng, ['class' => 'form-control']) }}
                        </div>


                        <div class="form-group">
                            {{ Form::label('Owner', null, ['class' => 'control-label']) }}
                            {{ Form::select('hscontact_id', $hscontacts, $currhscontact, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Location batteries ', null, ['class' => 'control-label']) }}
                            {{ Form::text('batteries', $location->batteries, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Location Standby time (hrs)', null, ['class' => 'control-label']) }}
                            {{ Form::text('standbytime', $location->standbytime, ['class' => 'form-control']) }}
                        </div>



                        <div class="form-group">
                            {{ Form::label('Location images ', null, ['class' => 'control-label']) }}
                            {{ Form::file('image') }}
                        </div>

                        <div class="form-group">
                            {{ Form::submit() }}

                        </div>

                        {{ Form::close() }}

                        <a style="font-size:35px;margin-left :2%;margin-top:2%;" href="/location/{{$location->id}}"
                           class="fa fa-arrow-circle-left" aria-hidden="true"></a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
