@extends('layouts.app')

@section('title', 'SLA Report')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">

                    <div class="panel-heading">All devices
                    </div>

                    <div class="panel-body">

                        <div id="links" style="position: relative">
                            <a href="{{ url('/reports/devices/month') }}"  class="btn btn-default">Uptime since the 1st of this Month</a>
                            <a href="{{ url('/reports/devices/week') }}"  class="btn btn-default"> Uptime since Monday</a>
                            <a href="{{ url('/reports/devices/day') }}"  class="btn btn-default"> Uptime yesterday</a>
                            <a href="{{ url('/reports/devices/24h') }}"  class="btn btn-default"> Uptime last 24h</a>
                            <a href="{{ url('/reports/devices/7days') }}"  class="btn btn-default"> Uptime last 7 days</a>
                            <a href="{{ url('/reports/devices/30days') }}"  class="btn btn-default"> Uptime last 30 days</a>
                            </br>
                            <div>
                                <h3>Report generated at {!!  gmdate("Y/m/d H:i:s",$filetime) !!} </h3>
                            </div>
                        </div>

                        <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Device IP</th>
                                    <th>Down Minutes</th>
                                    <th>ICMP Availability</th>
                                </tr>
                                </thead>
                                @foreach ($slareport as $row)
                                    <tr>
                                        <td>
                                            @if (array_key_exists('0',$row))
                                                {{$row['0']}}
                                            @endif
                                        </td>
                                        <td>
                                            @if (array_key_exists('1',$row))
                                                {{$row['1']}}
                                            @endif
                                        </td>
                                        <td>
                                            {{\App\Http\Controllers\SlaReportController::secondsToTime($row['2'])}}
                                        </td>
                                        <td>
                                            {{$row['3']}} %
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
