@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit client {{$client->name}}</div>

                    <div class="panel-body">


                        {!! Form::model($client,['method'=>'PATCH', 'route' => ['client.update', $client->id]]) !!}


                        <div class="form-group">
                            {{ Form::label('Name ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('name', $client->name, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Username ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('username', $client->username, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('IP Address ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('ip', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Reseller ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('reseller', $client->reseller, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Device Type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('devicetype_id', $devicetypes , $client->devicetype_id, ['class' => 'form-control']) }}
                        </div>


                        <div class="form-group">
                            {{ Form::label('Is this an enterprise client? ', null, ['class' => 'control-label']) }}
                            {{ Form::checkbox('is_enterprise', 1, true) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('location_id', $locations , $client->location_id, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Comment ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('comment', $client->comment, ['class' => 'form-control']) }}
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
