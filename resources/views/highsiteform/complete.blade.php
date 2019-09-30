
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            </br>
            </br>
            </br>
            <div class="panel panel-default">
                <div class="panel-heading">Complete the list below</div>
`
                                        <div class="panel-body">

                                            {!! Form::model($highsiteform,['method'=>'PATCH', 'route' => ['highsiteform.update', $highsiteform->id]]) !!}

                                            <div class="form-group">
                                                {{ Form::label('Stay cables checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('stays_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Power monitor on clean power', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('pm_checked_on power', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Batteries checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('batteries_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Number of Batteries', null, ['class' => 'control-label']) }}
                                                {{ Form::select('num_bats_checked', ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8']) }}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Cables crimps checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('check_cable_crimps', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Ports on the TS marked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('ports_marked_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Cables all marked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('ports_marked_checked', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Overall site tidyness checked', null, ['class' => 'control-label']) }}
                                                {{ Form::checkbox('overall_site_tidyness', '1')}}
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Reason for visit', null, ['class' => 'control-label']) }}
                                                {{ Form::text('reason',"") }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('Unresolved issues', null, ['class' => 'control-label']) }}
                                                {{ Form::text('unresolved_issues',"") }}
                                            </div>


                                            <div class="form-group" style="visibility: hidden">
                                                {{ Form::text('completed',"1") }}
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
    </div>

@endsection
