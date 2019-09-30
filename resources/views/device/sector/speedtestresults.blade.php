@extends('layouts.app')

@section('title', 'Save your results')

@section('content')



    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$device->name}}</div>
                    <div class="panel-body">

                        {!! Form::model($device,['method'=>'POST', 'url' => ['/test/store/'.$device->id]]) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading"> Speed Results</div>

                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('Max Download ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('last_download_test', null, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('Max Upload ', null, ['class' => 'control-label']) }}
                                    {{ Form::text('last_upload_test', null, ['class' => 'form-control']) }}
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
