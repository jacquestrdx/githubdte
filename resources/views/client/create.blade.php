@extends('layouts.app')
@section('title', 'Create VIP')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new VIP client</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'ClientController@secureRouterPost']) !!}

                        <div class="form-group">
                            {{ Form::label('Name ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Username ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('username', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('IP Address ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('ip', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Reseller ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('reseller', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Device Type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('devicetype_id', $devicetypes , null, ['class' => 'js-example-basic-single form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('location_id', $locations , null, ['class' => 'js-example-basic-single form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Is this an enterprise client? ', null, ['class' => 'control-label']) }}
                            {{ Form::checkbox('is_enterprise', 1, true) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Comment ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('comment', null, ['class' => 'form-control']) }}
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
