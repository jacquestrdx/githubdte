@extends('layouts.app')

@section('title', 'MikroTik Update Scheduler')

@section('content')
    <div class="container">
        <div class="row">
            <br/><br/><br/><br/>

            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">

                    <div class="panel-heading">MikroTik Update Scheduler

                    </div>

                    <div class="panel-body">
                        <table class="table hover">
                            <div class="panel-body">

                                <a href="{{ route('device.create') }}">
                                    <span style="" class="btn btn-primary btn-sm" title="Create">
                                    <span class="fa fa-plus-square"></span></span> Add a device
                                </a>
                                <div style="float : right">

                                    <div>{{$devices->count()}} in this filter</div>

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
                                            <li><a href="{{ route("device.scriptnotdeleted") }}">Mikrotiks remaining
                                                    script</a></li>
                                            <li><a href="{{ route("device.oldRos") }}">Mikrotiks old RouterOS</a></li>
                                            <li><a href="{{ route("device.scheduleupdates") }}">Mikrotik update
                                                    scheduler</a></li>
                                            <li><a href="{{ route("device.index") }}">All</a></li>
                                        </ul>
                                    </li>

                                </div>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Device IP</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Software</th>
                                    <th></th>
                                    <th>Clients</th>
                                    <th></th>
                                </tr>
                                @foreach ($devices as $device)
                                    <tr>
                                        <td>{{$device->name}}</td>

                                        <td>
                                            <a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('location.show',$device->location->id) }}">  {{$device->location->name}}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('devicetype.show',$device->devicetype->id) }}"> {{$device->devicetype->name}}</a>
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
                                            {!! Form::model($device,['method'=>'PATCH', 'route' => ['updatelist', $device->id]]) !!}
                                            {!! Form::checkbox('sch_update', 'sch_update', $device->sch_update) !!}
                                            {{ Form::submit('Schedule') }}
                                            {{ Form::close() }}
                                        </td>
                                        <td>
                                            @if ($device->devicetype_id == "1")
                                                {{$device->active_pppoe}}
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('device.edit',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                            </a>
                                        </td>


                                        <td>
                                            <a href="{{ route('device.update',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Update">
                                    <span class="fa fa-bolt "></span></span>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
