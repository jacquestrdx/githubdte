@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$location->name}}</div>

                    <div class="panel-body">

                        {!! Form::model($job,['method'=>'PATCH', 'route' => ['job.update', $job->id]]) !!}


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
