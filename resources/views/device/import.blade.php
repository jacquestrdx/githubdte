@extends('layouts.app')
@section('title', 'Import Devices')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Import devices</div>

                    <div class="panel-body">

                        {!! Form::open(['route' => ['devices.importcsv'],'files' => true]) !!}

                        <div class="form-group">
                            {{ Form::label('Import your list of devices here ', null, ['class' => 'control-label']) }}
                            {{ Form::file('file'),null, ['class' => 'control-label'] }}
                        </div>

                        <div class="form-group">
                            <div class="control-label">
                                Click <a href='/devices/downloadtemplate'>Here!!</a> to download template
                                <div class="alert alert-warning">
                                    <p>Please note that the template requires a lookup ID </p>
                                    <p>This means you will have to match the location to the id and the device type to a id</p>
                                    <p>Take note of the example entry in the template</p>
                                    <p>You can download CSV files with the IDs from</p>
                                    <ul>
                                        <li><a href="/location">Locations</a></li>
                                        <li><a href="/devicetype">Device types</a></li>
                                    </ul>
                                </div>
                            </div>

                        <div class="form-group">
                            {!! Form::submit("IMPORT", ['class' => 'btn btn-default']) !!}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
