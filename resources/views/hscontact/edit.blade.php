@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$hscontact->name}}</div>

                    <div class="panel-body">


                        {!! Form::model($hscontact,['method'=>'PATCH', 'route' => ['hscontact.update', $hscontact->id]]) !!}


                        <div class="form-group">
                            {{ Form::label('Name ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Surname ', null, ['class' => 'control-label']) }}
                            {{ Form::text('surname', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Cell number', null, ['class' => 'control-label']) }}
                            {{ Form::text('cellnum', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Cell number 2', null, ['class' => 'control-label']) }}
                            {{ Form::text('cellnum2', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Email address', null, ['class' => 'control-label']) }}
                            {{ Form::text('email', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Physical Address ', null, ['class' => 'control-label']) }}
                            {{ Form::text('address', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>
                        {{ Form::close() }}

                        <a style="font-size:40px" href="/hscontact/{{$hscontact->id}}" class="fa fa-arrow-circle-left"
                           aria-hidden="true"></a>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
