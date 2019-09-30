@extends('layouts.app')

@section('title', 'Edit device type')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$devicetype->name}}</div>

                    <div class="panel-body">

                        {!! Form::model($devicetype,['method'=>'PATCH', 'route' => ['devicetype.update', $devicetype->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Device type name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>

                        {{ Form::close() }}

                        <a style="font-size:40px" href="{{ url('devicetype', $devicetype->id) }}"
                           class="fa fa-arrow-circle-left" aria-hidden="true"></a>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
