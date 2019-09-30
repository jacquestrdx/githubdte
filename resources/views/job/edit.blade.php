@extends('layouts.app')

@section('title', 'Edit job')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Job</div>

                    <div class="panel-body">

                        {!! Form::model($job,['method'=>'PATCH', 'route' => ['job.update', $job->id]]) !!}

                        <div class="form-group">
                            {{ Form::label('Date done ', null, ['class' => 'control-label']) }}
                            {{Form::date('date', \Carbon\Carbon::now())}}

                        </div>

                        <div class="form-group">
                            {{ Form::label('Location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('location_id', $locations, $job->location_id, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Technician ', null, ['class' => 'control-label']) }}
                            {{ Form::select('technician', ['CorrieE','EstevanD','PieterG','LucasC'], $current_technician, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Registration Number ', null, ['class' => 'control-label']) }}
                            {{ Form::select('reg_nr', ['DK 61 YT GP','DJ 96 TY GP','DJ 92 VG GP','DJ 96 TZ GP','FJ 96 TY GP','CA W4 91 04','CA W4 47 81','FJ 96 RY GP'], $current_reg, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Time spent(hrs) ', null, ['class' => 'control-label']) }}
                            {{ Form::text('time_spent', $job->time_spent, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Start km ', null, ['class' => 'control-label']) }}
                            {{ Form::text('start_km', $job->start_km, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('End km ', null, ['class' => 'control-label']) }}
                            {{ Form::text('end_km', $job->end_km, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Fault description ', null, ['class' => 'control-label']) }}
                            {{ Form::select('fault_description', ['Breakdown','Maintenance','Relocation','New Install','Preventative Maintenance','Support Mail'], $current_fault, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Resolution ', null, ['class' => 'control-label']) }}
                            {{ Form::text('resolution', $job->resolution, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Live ? ', null, ['class' => 'control-label']) }}
                            {{ Form::select('fiz_live', ["No","Yes"], ["No"], ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Signal ', null, ['class' => 'control-label']) }}
                            {{ Form::text('signal', $job->signal, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('CCQ ', null, ['class' => 'control-label']) }}
                            {{ Form::text('ccq', $job->ccq, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('PI Down ', null, ['class' => 'control-label']) }}
                            {{ Form::text('pi_down', $job->pi_down, ['class' => 'form-control']) }}

                            {{ Form::label('PI Up ', null, ['class' => 'control-label']) }}
                            {{ Form::text('pi_up', $job->pi_up, ['class' => 'form-control']) }}

                            <br>
                            </br>
                            {{ Form::label('PI Latency ', null, ['class' => 'control-label']) }}
                            {{ Form::text('pi_latency', $job->pi_latency, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('Mweb Down ', null, ['class' => 'control-label']) }}
                            {{ Form::text('mweb_down', $job->mweb_down, ['class' => 'form-control']) }}

                            {{ Form::label('Mweb Up ', null, ['class' => 'control-label']) }}
                            {{ Form::text('mweb_up', $job->mweb_up, ['class' => 'form-control']) }}

                            <br>
                            </br>
                            {{ Form::label('MWEB Latency ', null, ['class' => 'control-label']) }}
                            {{ Form::text('mweb_latency', $job->mweb_latency, ['class' => 'form-control']) }}
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
