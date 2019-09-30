@extends('layouts.app')

@section('title', 'All interfaces')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Devices
                        <a href="{{ route('device.create') }}" style="float:right">
                            <span class="fa fa-plus-square"></span> Add
                        </a>
                    </div>

                    <div class="panel-body">
                        <div class="dataTable_wrapper" id="devicestable">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                    <tr>
                                        <th>Device Name</th>
                                        <th>Device Interface</th>
                                        <th>RX</th>
                                        <th>TX</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($devices as $deviceid => $devicestats)
                                            @php
                                                $devicename = App\Device::getNameFromID($deviceid);
                                            @endphp
                                        @foreach ($devicestats as $interfacename => $interfacestats)
                                            <tr>
                                                <td><a href="/device/{{$deviceid}}">{{$devicename}}</a></td>
                                                <td>{{$interfacename}}</td>
                                                <td>{{$interfacestats['rxvalue']}}</td>
                                                <td>{{$interfacestats['txvalue']}}</td>
                                                <td>{{$interfacestats['time']}}</td>
                                            @endforeach
                                        @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{--
                --}}
        </div>
    </div>
@endsection
