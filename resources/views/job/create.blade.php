@extends('layouts.app')

@section('title', 'Create job')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">New Job</div>

                <div class="panel-body">

                    {!! Form::open(['action'=>'JobController@store']) !!}

                    <div class="form-group">
                        {{ Form::label('Date done ', null, ['class' => 'control-label']) }}
                        {{Form::date('date', \Carbon\Carbon::now())}}

                    </div>

                    <div class="form-group">
                        {{ Form::label('Location ', null, ['class' => 'control-label']) }}
                        {{ Form::select('location_id', $locations, null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Technician ', null, ['class' => 'control-label']) }}
                        {{ Form::select('technician', ['CorrieE','EstevanD','PieterG','LucasC'], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Registration Number ', null, ['class' => 'control-label']) }}
                        {{ Form::select('reg_nr', ['DK 61 YT GP','DJ 96 TY GP','DJ 92 VG GP','DJ 96 TZ GP','FJ 96 TY GP','CA W4 91 04','CA W4 47 81','FJ 96 RY GP'], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Time spent(hrs) ', null, ['class' => 'control-label']) }}
                        {{ Form::text('time_spent', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Start km ', null, ['class' => 'control-label']) }}
                        {{ Form::text('start_km', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('End km ', null, ['class' => 'control-label']) }}
                        {{ Form::text('end_km', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Fault description ', null, ['class' => 'control-label']) }}
                        {{ Form::select('fault_description', ['Breakdown','Maintenance','Relocation','New Install','Preventative Maintenance','Support Mail'], null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Resolution ', null, ['class' => 'control-label']) }}
                        {{ Form::text('resolution', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Live ? ', null, ['class' => 'control-label']) }}
                        {{ Form::select('fiz_live', ["No","Yes"], ["No"], ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Signal ', null, ['class' => 'control-label']) }}
                        {{ Form::text('signal', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('CCQ ', null, ['class' => 'control-label']) }}
                        {{ Form::text('ccq', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('PI Down ', null, ['class' => 'control-label']) }}
                        {{Form::selectRange('pi_down', 0, 20)}}
                        {{ Form::label('PI Up ', null, ['class' => 'control-label']) }}
                        {{Form::selectRange('pi_up', 0, 20)}}
                    <br>
                    </br>
                        {{ Form::label('PI Latency ', null, ['class' => 'control-label']) }}
                        {{ Form::text('pi_latency', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('Mweb Down ', null, ['class' => 'control-label']) }}
                        {{Form::selectRange('mweb_down', 0, 20)}}
                        {{ Form::label('Mweb Up ', null, ['class' => 'control-label']) }}
                        {{Form::selectRange('mweb_up', 0, 20)}}
                    <br>
                    </br>
                        {{ Form::label('MWEB Latency ', null, ['class' => 'control-label']) }}
                        {{ Form::text('mweb_latency', null, ['class' => 'form-control']) }}
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
