@extends('layouts.app')

@section('title', 'Create Stock')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">New Stock</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'StockController@store']) !!}

                    <div class="form-group">
                        {{ Form::label('Stock Item ', null, ['class' => 'control-label']) }}
                        {{ Form::select('description', [
                            'Tough Switch',
                            'Power Beam',
                            'Rocket',
                            '48V POE',
                            '24V POE',
                            '0.5M Flylead',
                            '1M Flylead',
                            '5M Flylead',
                            'Cat 5e (m)',
                            'Power Cable (m)',
                            'Surge Arrestor',
                            'Offset Bracket',
                            'Steel Pole (m)',
                            'Mikrotik 951',
                            'Ruckus T300',
                            'Ruckus T301',
                            'Nano Station M5 ',
                            'Nano Station M2 '
                         ], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Qty ', null, ['class' => 'control-label']) }}
                        {{ Form::text('qty', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Serial ', null, ['class' => 'control-label']) }}
                        {{ Form::text('serial', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::hidden('job_id', $id) }}
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
