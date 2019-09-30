@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$bgppeer->device->name}}</div>
                    <div class="panel-body">

                        {!! Form::model($bgppeer,['method'=>'PATCH', 'route' => ['bgppeer.update', $bgppeer->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Note ', null, ['class' => 'control-label']) }}
                            {{ Form::text('ack_note') }}
                        </div>

                        <div class="form-group" style="visibility: hidden">
                            {{ Form::text('acknowledged',"1") }}
                        </div>
                        <div class="form-group" style="visibility: hidden">
                            {{ Form::text('ack_user_id', Auth::user()->id ) }}
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
