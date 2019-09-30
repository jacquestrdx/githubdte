
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a Custom OID</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'CustomsnmpoidController@store']) !!}
                    
                    <div class="form-group">
                    {{ Form::label('OID ', null, ['class' => 'control-label']) }}
                    {{ Form::text('oid_to_poll') }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Snmp Community ', null, ['class' => 'control-label']) }}
                    {{ Form::text('snmp_community') }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Math ', null, ['class' => 'control-label']) }}
                        {{ Form::text('math') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Value Name ', null, ['class' => 'control-label']) }}
                        {{ Form::text('value_name') }}
                    </div>

                    <input type="hidden" name="device_id" value="{!! $id !!}">

                    <div class="form-group">
                        <input type="submit" name="action" value="Create" />
                        <input type="submit" name="action" value="Test" />
                    </div>
                    {{ Form::close() }}


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
