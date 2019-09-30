@extends('layouts.app')

@section('title', 'Edit Custom OID')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$customoid->oid_to_poll}} {!! $customoid->device->name !!}</div>

                    <div class="panel-body">

                        {!! Form::model($customoid,['method'=>'PATCH', 'route' => ['customsnmpoid.update', $customoid->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('OID ', null, ['class' => 'control-label']) }}
                            {{ Form::text('oid_to_poll', $customoid->oid_to_poll, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Snmp Community ', null, ['class' => 'control-label']) }}
                            {{ Form::text('snmp_community', $customoid->snmp_community, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Value Name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('value_name', $customoid->value_name, ['class' => 'form-control']) }}
                        </div>

                        <input type="hidden" name="device_id" value="{!! $customoid->device_id !!}">

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
