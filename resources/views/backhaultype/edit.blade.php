@extends('layouts.app')

@section('title', 'Edit Backhaultype')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Updating {{$backhaultype->name}}</div>

                    <div class="panel-body">

                        {!! Form::model($backhaultype,['method'=>'PATCH', 'route' => ['backhaultype.update', $backhaultype->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Description ', null, ['class' => 'control-label']) }}
                            {{ Form::text('name', $backhaultype->name, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Colour ', null, ['class' => 'control-label']) }}
                            {{ Form::text('color', $backhaultype->color, ['class' => 'form-control']) }}
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
