@extends('layouts.app')

@section('title', 'Down BGP Peers')

@section('content')

<div class="container">

    <div class="row">
        <div class="col-md-12 col-md-offset-0">
                
            <div class="panel panel-default">
                
                <div class="panel-heading" style="font-weight:bold"><strong>Down BGP Peers</strong></div>

                <div class="panel-body">


                        <div class="row">
                            <div class="col-md-12">
                                <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                        <thead>
                                        <tr>
                                        <th>Device</th>
                                        <th>Name</th>
                                        <th>Local Ip</th>
                                        <th>Remote IP</th>
                                        <th>Remote as</th>
                                        <th>State</th>
                                        <th>Updated</th>
                                        <th>Acknowledge</th>
                                        <th>Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($devices as $device)
                                            @if ($device->devicetype_id == "1" AND count($device->bgppeers))
                                            @foreach ($device->bgppeers as $bgppeer)
                                                @if (($bgppeer->disabled=="false") AND ($bgppeer->state!="established"))
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('device.show',$device->id) }}">
                                                            {{$device->name}}
                                                        </a>
                                                    </td>
                                                    <td>{{$bgppeer->name}}</td>
                                                    <td>
                                                        <a href="{{ route('device.show',$device->id) }}">
                                                            {{$device->ip}}
                                                        </a>
                                                    </td>
                                                    <td>{{$bgppeer->remote_address}}</td>
                                                    <td>{{$bgppeer->remote_as}}</td>
                                                    <td>{{$bgppeer->state}}</td>
                                                    <td>{{$bgppeer->updated_at}}</td>
                                                    <td>
                                                        @if ($bgppeer->acknowledged != "1")
                                                    <a href="{{ route('bgppeer.acknowledge',$bgppeer->id) }}">
                                                        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                                        <span class="sr-only">Loading...</span>
                                                    </a>
                                                        @else
                                                            <a href="{{ route('device.show',$bgppeer->device_id) }}" title="{{$bgppeer->getAckUser() }}">
                                                                <i class="fa fa-check" aria-hidden="true" ></i>
                                                                <span class="sr-only">Loading...</span>
                                                                {{$bgppeer->getAcknowledgementNote()}}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (\Auth::user()->user_type=="admin")
                                                            <a class="confirm" style="color:darkred;float:right" href="/bgppeer/delete/{{$bgppeer->id}}">Delete
                                                                <span class="btn btn-danger btn-sm" title="Delete">
                                                                <span style="color:red" class="fa fa-minus-circle "></span></span>
                                                            </a>
                                                            </br>
                                                        @endif
                                                    </td>
                                                </tr>
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
</div>
@endsection
