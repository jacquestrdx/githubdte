
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new device</div>

                <div class="panel-body">



                    {!! Form::open(['action'=>'HscontactController@store']) !!}
                    

                    <div class="form-group">
                    {{ Form::label('Name ', null, ['class' => 'control-label']) }}
                    {{ Form::text('name') }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Surname ', null, ['class' => 'control-label']) }}
                    {{ Form::text('surname') }}
                    </div>

                   <div class="form-group">
                    {{ Form::label('Cell number', null, ['class' => 'control-label']) }}
                    {{ Form::text('cellnum') }}
                    </div>

                   <div class="form-group">
                    {{ Form::label('Cell number 2', null, ['class' => 'control-label']) }}
                    {{ Form::text('cellnum2') }}
                    </div>

                   <div class="form-group">
                    {{ Form::label('Email address', null, ['class' => 'control-label']) }}
                    {{ Form::text('email') }}
                    </div>

                   <div class="form-group">
                    {{ Form::label('Physical Address ', null, ['class' => 'control-label']) }}
                    {{ Form::text('address') }}
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
