@extends('layouts.app')

@section('title', 'High Site Report')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">High Site Report
                        <a style="float:right;" href="http://dte.bronbergwisp.co.za/downloadhighsitereport">
                            Get CSV
                        </a>
                    </div>
                    <div class="panel-body">
                                @foreach ($locations as $location)
                                    <h4>{{$location->name}}</h4>
                                    <table class="table hover">
                                        <thead>
                                            <th>Device name</th>
                                            <th>Device IP</th>
                                            <th>Device model</th>
                                            <th>Device Serial/Mac</th>
                                        </thead>
                                        @foreach ($location->device as $device)
                                            <tr>
                                                <td><a href="{{ route('device.show', $device->id) }}">  {{$device->name}}</a></td>
                                                <td><a href="{{ route('device.show', $device->id) }}">  {{$device->ip}}</a></td>
                                                <td>{{$device->model}}</td>
                                                    <td>
                                                        @if ($device->devicetype_id == "1")
                                                            {{$device->active_pppoe}}
                                                        @endif
                                                        @if ($device->devicetype_id == "2")
                                                            {{$device->active_stations}}
                                                        @endif
                                                    </td>
                                            </tr>
                                        @endforeach
                                    </table>

                        @endforeach
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
