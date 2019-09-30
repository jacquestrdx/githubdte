@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$nmap->description}}</div>

                    <div class="panel-body">


                        {!! Form::model($nmap,['method'=>'PATCH', 'route' => ['nmap.update', $nmap->id]]) !!}


                        <div class="form-group">
                            {{ Form::label('Description ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('description', $nmap->description, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('subnet ', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('subnet', $nmap->subnet, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 1', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_1', $nmap->port_1, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 2', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_2', $nmap->port_2, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('Port 3', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_3', $nmap->port_3, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">

                            {{ Form::label('Port 4', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_4', $nmap->port_4, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">

                            {{ Form::label('Port 5', null, ['class' => 'control-label col-md-3']) }}
                            <div class="col-md-5">
                                {{ Form::text('port_5', $nmap->port_5, ['class' => 'form-control']) }}
                            </div>
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
