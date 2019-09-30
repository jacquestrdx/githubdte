@extends('layouts.app')

@section('title', 'Top 20 Report')

@section('content')
    <div class="container">

                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="height: 55px">Top 40 {!! $type !!}
                            <div style="float:right">
                                <a href="#" class="btn btn-default" onclick="close_window();return false;">Close</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <th>Device SSID</th>
                                    <th>Device Name</th>
                                    <th>Device IP</th>
                                    <th>Device Type</th>
                                    <th>Device Location</th>
                                    <th>Device Stations</th>
                                    <th>Bandwidth Sold</th>
                                    <th>Updated at</th>
                                </thead>
                                <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <td>
                                                <a href="/device/{!! $device->id !!}">
                                                    {!! $device->ssid !!}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="/device/{!! $device->id !!}">
                                                    {!! $device->name !!}
                                                </a>
                                            </td>

                                            <td>
                                                <a href="/device/{!! $device->id !!}">
                                                    {!! $device->ip !!}
                                                </a>
                                            </td>
                                            <td>
                                                {!! $device->devicetype->name !!}
                                            </td>
                                            <td>
                                                <a href="/location/{!! $device->location_id !!}">
                                                    {!! $device->location->name !!}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="/device/{!! $device->id !!}">
                                                    {!! $device->active_stations !!}
                                                </a>
                                            </td>
                                            <td>
                                                {!! $device->comment !!}
                                            </td>
                                            <td>
                                                {!! $device->lastsnmpupdate !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


    </div>

    @push('scripts')
    <script>
        function close_window() {
            if (confirm("Close Window?")) {
                close();
            }
        }
    </script>

    @endpush

@endsection
