@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$user->email}}</div>
                    <div class="panel-body">

                        {!! Form::model($user,['method'=>'PATCH', 'route' => ['user.update', $user->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('User name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>
                        @php
                            if($user->user_type == "CC"){
                                $usertype = "0";
                            }
                            if($user->user_type == "admin"){
                                    $usertype = "1";
                                }
                        @endphp

                        <div class="form-group">
                            {{ Form::label('User type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('user_type', ["CC","admin"], $usertype, ['class' => 'form-control']) }}
                        </div>

                        @if($user->receive_reports=="1")
                            <div class="form-group">
                                {{ Form::label('Receive Reports', null, ['class' => 'control-label']) }}
                                {{ Form::checkbox('receive_reports', 'receive_reports', true ) }}
                            </div>
                        @else
                            <div class="form-group">
                                {{ Form::label('Receive Reports', null, ['class' => 'control-label']) }}
                                {{ Form::checkbox('receive_reports', 'receive_reports', false) }}
                            </div>
                        @endif


                        @if($user->receive_notifications=="1")
                            <div class="form-group">
                                {{ Form::label('Receive notifications', null, ['class' => 'control-label']) }}
                                {{ Form::checkbox('receive_notifications', 'receive_notifications', true ) }}
                            </div>
                        @else
                            <div class="form-group">
                                {{ Form::label('Receive notifications', null, ['class' => 'control-label']) }}
                                {{ Form::checkbox('receive_notifications', 'receive_notifications', false) }}
                            </div>
                        @endif


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
