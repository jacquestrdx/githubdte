@extends('layouts.app')

@section('title', 'Create Device')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create comment</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'CommentController@store']) !!}

                        <div class="form-group">
                            {{ Form::label('Comment ', null, ['class' => 'control-label']) }}
                            {{ Form::text('comment', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::hidden('id', $id, ['class' => 'form-control']) }}
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
