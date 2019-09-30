
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a backhaultype</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'BackhaultypeController@store']) !!}
                    
                    <div class="form-group">
                    {{ Form::label('Name ', null, ['class' => 'control-label']) }}
                    {{ Form::text('name') }}
                    </div>

                    <div class="form-group">
                    {{ Form::label('Color ', null, ['class' => 'control-label']) }}
                    {{ Form::text('color') }}
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
