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

                        <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Down Minutes</th>
                                    <th>ICMP Availability</th>
                                </tr>
                                </thead>
                                @foreach ($slareport as $row)
                                    <tr>
                                        <td>
                                            <a href="{{ route('device.show', $device->getDeviceIDFromName($row['0'])) }}">  {{$row['0']}}</a>

                                        </td>
                                        <td>
                                            {{$row['1']}}
                                        </td>
                                        <td>
                                            {{$row['2']}}
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
