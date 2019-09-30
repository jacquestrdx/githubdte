@extends('layouts.app')

@section('title', 'Devices')

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

                        <div style="float : right">

                            <div>{{$devices->count()}} in this filter</div>

                            <ul>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-expanded="false">Filter
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach ($devicetypes as $devicetype)
                                            <li>
                                                <a href="{{ route("device.sortby",$devicetype->id) }}">{{$devicetype->name}}</a>
                                            </li>
                                    @endforeach
                                    <!--<li><a href="{{ route("device.scriptnotdeleted") }}">Mikrotiks remaining script</a></li>-->

                                        <li><a href="{{ route("device.noLocations") }}">Location Unknown</a></li>
                                        <li><a href="{{ route("device.oldRos") }}">Mikrotiks old RouterOS</a></li>
                                        <li><a href="{{ route("device.faulty") }}">Faulty devices</a></li>
                                        <li><a href="{{ route("device.scheduleupdates") }}">Mikrotik update
                                                scheduler</a>
                                        </li>
                                        <li><a href="{{ route("device.index") }}">All</a></li>

                                    </ul>
                                </li>
                            </ul>

                        </div>
                        <div class="row"></div>

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th style="column-width: 8%">Device Name</th>
                                    <th style="column-width: 8%">Device IP</th>
                                    <th style="column-width: 8%">Ping</th>
                                    <th style="column-width: 8%">Software</th>
                                    <th style="column-width: 8%">Clients</th>
                                    <th style="column-width: 8%">Default Gateway</th>
                                    <th style="column-width: 8%">Last Updated</th>
                                    <th style="column-width: 8%">Last Backup</th>
                                    <th style="column-width: 8%">Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($devices as $device)
                                    <tr>
                                        <td>
                                            @if ($device->devicetype_id == "1")
                                                <i class="fa fa-server" aria-hidden="true"></i>
                                            @endif

                                            {{$device->name}}

                                        </td>

                                        <td>
                                            <a href="{{ route('device.show', $device->id) }}">  {{$device->ip}}</a>
                                        </td>
                                        @if($device->ping==1)
                                            <td style="color:green">Up</td>
                                        @else
                                            <td style="color:red">Down</td>
                                        @endif

                                        <td>
                                            {{$device->soft}}
                                        </td>
                                        <td>
                                            @if ($device->devicetype_id == "1")
                                                {{$device->active_pppoe}}
                                            @elseif ($device->devicetype_id == "2")
                                                {{$device->active_stations}}
                                            @endif

                                        </td>

                                        <td>
                                            {{$device->default_gateway}}
                                        </td>


                                        @if (($device->lastsnmpupdate > $formatted_date) AND ($device->devicetype_id != "4") AND ($device->devicetype_id != "3"))
                                            <td style='color:green'>
                                                {{ $device->lastsnmpupdate }}
                                            </td>
                                        @elseif (($device->lastsnmpupdate < $formatted_date) AND ($device->devicetype_id != "4" AND ($device->devicetype_id != "3")))
                                            <td style='color:red'>
                                                {{ $device->lastsnmpupdate }}
                                            </td>
                                        @endif

                                        @if ($device->backed_up=="1")
                                            <td style="color:green">{{date("Y M d", strtotime($device->date_backed_up))}}
                                                <a href="{{ route('device.backupdevice',$device->id) }}"  target="_blank"><span class="btn btn-primary btn-sm" title="Update from Mikrotik">
                                                <span style="max-height: 15%;" class="fa fa-bolt"></span></span></a>
                                                <a href="{{ route('device.downloadbackup',$device->id) }}"  target="_blank">
                                                <span class="btn btn-primary btn-sm" title=" Download to local">
                                                    <span class="fa fa-cloud-download"></span></span></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('device.backupdevice',$device->id) }}"  target="_blank"><span class="btn btn-primary btn-sm" title="Update from Mikrotik">
                                                <span style="max-height: 15%;" class="fa fa-bolt"></span></span></a>
                                            </td>
                                        @endif

                                        @if ($device->devicetype_id == "4" or $device->devicetype_id =="3")
                                            <td style='color:green'>
                                                {{ $device->lastseen }}
                                            </td>
                                        @endif

                                        <td>
                                            <a href="{{ route('device.edit',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
