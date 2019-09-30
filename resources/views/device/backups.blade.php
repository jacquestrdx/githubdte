@extends('layouts.app')

@section('title', 'Backups')

@section('content')

<div class="container">
    <div class="row">

        <div class="col-md-12 col-md-offset-0">
            
            <div class="panel panel-default">

                <div class="panel-heading">All Devices
 
                </div>

                <div class="panel-body">

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                               id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                            <tr>
                                <th>Device Name</th>
                                <th>Device IP</th>
                                <th>Status</th>
                                <th>Date created</th>
                                <th>Backup now</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($devices as $device)
                                @if ($device->devicetype_id =="1")
                                <tr>
                                    <td>{{$device->name}}</td>
                                    <td><a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a></td>
                                    <td>
                                        @if ($device->backed_up=="1")
                                            <i class="fa fa-check-circle" aria-hidden="true" style="color:green"></i>
                                        @else
                                            <i class="fa fa-times-circle-o" aria-hidden="true" style="color:red"></i>
                                        @endif
                                    </td>
                                    @if ($device->backed_up=="1")
                                        <td style="color:green">{{date("Y M d", strtotime($device->date_backed_up))}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        <a href="{{ route('device.backupdevice',$device->id) }}"  target="_blank"><span class="btn btn-primary btn-sm" title="Update from Mikrotik">
                                    <span class="fa fa-bolt "></span></span></a>
                                    </td>
                                    <td>
                                        <a href="{{ route('device.downloadbackup',$device->id) }}"  target="_blank">
                            <span class="btn btn-primary btn-sm" title=" Download to local">
                                <span class="fa fa-cloud-download"></span></span></a>
                                    </td>

                                </tr>
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
@endsection
