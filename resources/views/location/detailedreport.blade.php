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
                                            <th>Device Name</th>
                                            <th>Device IP</th>
                                            <th>Device Model</th>
                                            <th>Device Serial / MAC</th>
                                        </thead>
                                        @foreach ($location->device as $device)
                                            <tr>
                                                <td><a href="{{ route('device.show', $device->id) }}">  {{$device->name}}</a></td>
                                                <td><a href="{{ route('device.show', $device->id) }}">  {{$device->ip}}</a></td>
                                                <td>{{$device->model}}</td>
                                                <td>{!! $device->serial_no !!}</td>
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
