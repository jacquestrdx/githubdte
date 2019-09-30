
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">All Devices
 
                </div>

                <div class="panel-body">
                <table class="table hover">
                <div class="panel-body">
                
                   <a style="align:right" href="{{ route('device.create') }}">
                                    <span style="" class="btn btn-primary btn-sm" title="Create">
                                    <span class="fa fa-plus-square"></span></span> Add a device
                                </a>
                <tr>
                <th>Device Name</th>
                <th>Device IP</th><th>Location</th>
                <th>Device Type</th><th>Ping Status</th><th></th><th></th></tr>    
                @foreach ($devices as $device)
                <tr>
                    <td>{{$device->name}}</td>
                    
                    <td>
                      <a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a>
                    </td> 
                    <td>
                        <a href="/location/{{$device->location->id}}">  {{$device->location->name}}</a>
                    </td>
                    <td>
                        <a href="/location/{{$device->devicetype->id}}">  {{$device->devicetype->name}}</a>
                    </td>  

                            @if($device->ping==1)
                                <td style="color:green">Up</td> 
                            @else
                                <td style="color:red">Down</td>
                            @endif 
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
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
