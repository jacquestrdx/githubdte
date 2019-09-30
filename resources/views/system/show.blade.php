@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">System Attributes

                    @if (\Auth::user()->user_type=="admin")
                        <a href="{{ route('system.edit', 1) }}">Edit</a>
                    @else
                    @endif
                </div>
                @if (\Auth::user()->user_type=="admin")
                    <div class="panel-body">
                        <h3>Details about running Tasks</h3>
                        <table class="table table-hover">
                            <tr><td>Map Longitude Centre</td><td>{{$system->longitude}}</td></tr>
                            <tr><td>Map Latitude Centre</td><td>{{$system->latitude}}</td></tr>
                            <tr><td>Snmp Community</td><td>{{$system->ubnt_snmpcommunity}}</td></tr>
                            <tr>
                                <td>Include Hotspot users in Radius Dial</td>
                                <td>
                                    @if($system->include_hotspot=="1")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                            </tr>
                            {{--<tr><td>Enable Register</td><td>{{$system->enable_register}}</td></tr>--}}
                            {{--<tr><td>Enable Polling</td><td>{{$system->enable_polling}}</td></tr>--}}
                            <tr><td>SMTP Server IP</td><td>{{$system->smtp_ip}}</td></tr>
                            <tr><td>Interval for reports in (hrs)</td><td>{!! $system->HourlyReportInterval !!}</td></tr>
                        </table>
                    </div>
                @else
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
