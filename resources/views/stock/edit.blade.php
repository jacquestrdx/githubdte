@extends('layouts.app')

@section('title', 'Edit stock')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating </div>

                    <div class="panel-body">

                        {!! Form::model($stock,['method'=>'PATCH', 'route' => ['stock.update', $stock->id]]) !!}


                        <div class="form-group">
                            {{ Form::label('Description ', null, ['class' => 'control-label']) }}
                            {{ Form::text('description', $stock->description, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Qty ', null, ['class' => 'control-label']) }}
                            {{ Form::text('qty', $stock->qty, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Serial ', null, ['class' => 'control-label']) }}
                            {{ Form::text('serial', $stock->serial, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::hidden('job_id', $stock->job_id) }}
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
