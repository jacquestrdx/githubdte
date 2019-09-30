@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All Devices at this location</div>
                    <div class="panel-body">
                        <table class="table hover">
                            <tr>
                                <th>Device Name</th>
                                <th>Device IP</th>
                                <th>Freq</th>
                                <th>Device Type</th>
                                <th>Stations</th>
                                <th>Model</th>
                                <th>Soft</th>
                                <th>CCQ</th>
                                <th>Noise floor</th>
                            </tr>
                            @foreach ($location->device as $device)
                                @if(($device->devicetype_id == "2") or ($device->devicetype_id == "22") or ($device->devicetype_id == "17"))
                                <tr>
                                    <td>{{$device->name}}</td>
                                    <td>
                                        <a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a>
                                    </td>
                                    <td>
                                        {{$device->freq}}
                                    </td>

                                    <td>
                                        {{$device->devicetype->name}}
                                    </td>
                                    <td>
                                        {{$device->active_stations}}
                                    </td>
                                    <td>
                                        {{$device->model}}
                                    </td>
                                    <td>
                                        {{$device->soft}}
                                    </td>
                                    <td>
                                        {{$device->avg_ccq}}
                                    </td>
                                    <td>
                                        Coming soon
                                    </td>


                                </tr>
                                @endif
                            @endforeach

                            <tr>
                                <td>
                                    <a style="float:right" href="{{ route('device.create') }}">
                                    <span style="" class="btn btn-primary btn-sm" title="Create">
                                    <span class="fa fa-plus-square"></span></span> Add a device
                                    </a>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>




            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mac Address</th>
                                    <th>Last IP</th>
                                    <th>Latency</th>
                                    <th>CCQ</th>
                                    <th>Signal</th>
                                    <th>Rates</th>
                                    <th>SSID</th>
                                    <th>Time Connected</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($location->device as $device)
                                    @if ($device->devicetype_id == "2" AND count($device->statables))
                                        @foreach ($device->statables as $statable)
                                            <tr>
                                                <td>{{$statable->name}}</td>
                                                <td>{{$statable->mac}}</td>
                                                <td>{{$statable->ip}}</td>
                                                <td>{{$statable->latency}}</td>
                                                <td>{{$statable->ccq}}</td>
                                                <td>{{$statable->signal}}</td>
                                                <td>{{$statable->rates}}</td>
                                                <td>
                                                    <a href="{{ route('device.show',$statable->device->id) }}">{{$statable->device->ssid}}</a>
                                                </td>
                                                <td>{{$statable->time}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong>High Site forms</strong></div>
                            <div class="panel-body">
                                <table class="table hover">
                                    <tr>
                                        <th style="width:50%;">Noc Agent</th>
                                        <th style="width:50%;">Field Agent</th>
                                        <th style="width:50%;">Spanning tree</th>
                                        <th style="width:50%;">Pm on clean power</th>
                                        <th style="width:50%;">Batteries Checked</th>
                                        <th style="width:50%;">No. Batteries</th>
                                        <th style="width:50%;">Cable Crimps</th>
                                        <th style="width:50%;">Site tidy</th>
                                        <th style="width:50%;">Cables marked</th>
                                        <th style="width:50%;">Routing checked</th>

                                    </tr>
                                    {{--@if (isset($location->highsiteforms))--}}
                                    @foreach ($highsiteforms as $highsiteform)
                                        <tr>
                                            <td>{{App\User::getname($highsiteform->noc_user_id)}}</td>
                                            <td>{{App\User::getname($highsiteform->field_user_id)}}</td>

                                            @if ($highsiteform->stp_checked =="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            @if ($highsiteform->pm_checked_on_power=="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            @if ($highsiteform->batteries_checked == "1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            <td>{{$highsiteform->num_bats_checked}}</td>
                                            @if ($highsiteform->check_cable_crimps =="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            @if ($highsiteform->overall_site_tidyness =="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            @if ($highsiteform->cables_marked =="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif

                                            @if ($highsiteform->routing_checked=="1")
                                                <td><i class="fa fa-check-circle" aria-hidden="true"
                                                       style="color:green"></i></td>
                                            @else
                                                <td><i class="fa fa-times-circle-o" aria-hidden="true"
                                                       style="color:red"></i></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    {{--@endif--}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
