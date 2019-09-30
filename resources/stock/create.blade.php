@extends('layouts.app')

@section('title', 'Create job')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">New Job</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'StockController@store']) !!}


                    <div class="form-group">
                        {{ Form::label('Description ', null, ['class' => 'control-label']) }}
                        {{ Form::text('description', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Qty ', null, ['class' => 'control-label']) }}
                        {{ Form::text('qty', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Serial ', null, ['class' => 'control-label']) }}
                        {{ Form::text('serial', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::hidden('job_id', $id) }}
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
