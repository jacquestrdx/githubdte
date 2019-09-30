@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new high site contact</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'HscontactController@store', 'class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            {{ Form::label('Name ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Surname ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('surname', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Cell number', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('cellnum', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Cell number 2', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('cellnum2', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Email address', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('email', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">

                            {{ Form::label('Physical Address ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('address', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        
                        <div class="col-md-1 col-md-offset-3">
                            {!! Form::submit("Create", ['class' => 'btn btn-default']) !!}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
