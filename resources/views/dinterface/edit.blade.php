@extends('layouts.app')

@section('title', 'Edit Interface')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$dinterface->name}}</div>

                    <div class="panel-body">

                        {!! Form::model($dinterface,['method'=>'PATCH', 'route' => ['dinterface.update', $dinterface->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Threshold in MB ', null, ['class' => 'control-label']) }}
                            {{ Form::text('threshhold', null, ['class' => 'form-control']) }}
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
