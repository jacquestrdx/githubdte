
@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            </br>
                </br>
                </br>
                </br>
                
            <div class="panel panel-default">
                
                
                <div class="panel-heading" style="font-style:bold"><strong>{{$client->name}}</strong></div>

                <div class="panel-body">

                    <table class="table hover">

                        <tr>
                            <th>Client Name</th>
                            <td>{{$client->name}}</td>
                        </tr>
                        <tr>
                            <th>Client IP</th>
                            <td>{{$client->ip}}</td>
                        </tr>
                        <tr>
                            <th>Client Location</th>
                            <td>{{$client->location->name}}</td>
                        </tr>
                        <tr>
                            <th>Client Device type</th>
                            <td>{{$client->devicetype->name}}</td>
                        </tr>
                            @if($client->ping==1)
                            <tr>
                                <th>Ping Status</th>
                                <td style="color:green">Up</td>
                            </tr>
                            @else
                            <tr>
                                <th>Ping Status</th>
                                <td style="color:red">Down</td>
                            </tr>
                            @endif
                        <tr>
                            <th>Last Update</th>
                            <td>{{$client->lastsnmpupdate}}</td>
                        </tr>

                            <tr>
                                <td>
                                    <a href="{{ route('client.edit',$client->id) }}">
                                        <span class="btn btn-primary btn-sm" title="Edit">
                                        <span class="fa fa-edit "></span></span>
                                    </a>

                                    <a href="{{ route('client.updateClient',$client->id) }}">
                                        <span class="btn btn-primary btn-sm" title="Update">
                                        <span class="fa fa-bolt "></span></span>
                                    </a>
                                </td>
                            </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
