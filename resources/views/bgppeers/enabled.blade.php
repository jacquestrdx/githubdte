@extends('layouts.app')

@section('title', 'BGP Peers')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading" style="font-weight:bold">
                        <strong>BGP Peers</strong>
                        <a style="float:right" href="{{ url('/bgppeers') }}">All</a>

                    </div>

                    <div class="panel-body">


                        <div class="row">

                            <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                       id="dataTables-example" role="grid"
                                       aria-describedby="dataTables-example_info">
                                    <thead>
                                    <tr>
                                        <th>Device</th>
                                        <th>Name</th>
                                        <th>Remote IP</th>
                                        <th>Remote as</th>
                                        <th>Remote Device</th>
                                        <th>State</th>
                                        <th>Prefix count</th>
                                        <th>Disabled</th>
                                        <th>Uptime</th>
                                        <th>Updated</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($devices as $device)
                                        @if ($device->devicetype_id == "1" AND count($device->bgppeers))
                                            @foreach ($device->bgppeers as $bgppeer)
                                                @if ($bgppeer->disabled=="false")
                                                    <tr>
                                                        <td>{{$device->name}}</td>
                                                        <td>{{$bgppeer->name}}</td>
                                                        <td>{{$bgppeer->remote_address}}</td>
                                                        <td>{{$bgppeer->remote_as}}</td>
                                                        <td>{{$device->getASNDeviceName($bgppeer->remote_as)}}</td>
                                                        <td>{{$bgppeer->state}}</td>
                                                        <td>{{$bgppeer->prefix_count}}</td>
                                                        <td>{{$bgppeer->disabled}}</td>
                                                        <td>{{$bgppeer->uptime}}</td>
                                                        <td>{{$bgppeer->updated_at}}</td>

                                                    </tr>
                                                    @else
                                                    {{--<tr style="color: grey">--}}
                                                    {{--<td>{{$device->name}}</td>--}}
                                                    {{--<td>{{$bgppeer->name}}</td>--}}
                                                    {{--<td>{{$bgppeer->remote_address}}</td>--}}
                                                    {{--<td>{{$bgppeer->remote_as}}</td>--}}
                                                    {{--<td>{{$device->getASNDeviceName($bgppeer->remote_as)}}</td>--}}
                                                    {{--<td>{{$bgppeer->state}}</td>--}}
                                                    {{--<td>{{$bgppeer->prefix_count}}</td>--}}
                                                    {{--<td>{{$bgppeer->disabled}}</td>--}}
                                                    {{--<td>{{$bgppeer->uptime}}</td>--}}
                                                    {{--<td>{{$bgppeer->updated_at}}</td>--}}
                                                    {{--</tr>--}}
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
