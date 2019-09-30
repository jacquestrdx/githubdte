@extends('layouts.app')

@section('title', 'Clients')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading">Clients
                        <div style="float : right">
                            <a href="{{ route('client.create') }}">
                                <span class="fa fa-plus-square"></span> Add
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="row"><br/><br/></div>
                        <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Client IP</th>
                                    <th>Location</th>
                                    <th>Device Type</th>
                                    <th>Ping</th>
                                    <th>Last Updated</th>
                                    <th>Edit</th>
                                    <th>Poll</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>
                                            <a href="{{ route('client.show',$client->id) }}">  {{$client->name}}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('client.show',$client->id) }}">  {{$client->ip}}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('location.show',$client->location->id) }}">  {{$client->location->name}}</a>
                                        </td>
                                        <td>{{$client->devicetype->name or ""}}</td>
                                        @if($client->ping==1)
                                            <td style="color:green">Up</td>
                                        @else
                                            <td style="color:red">Down</td>
                                        @endif
                                        <td>{{$client->lastsnmpupdate}}</td>

                                        <td>
                                            <a href="{{ route('client.edit',$client->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('client.update',$client->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Update">
                                    <span class="fa fa-bolt "></span></span>
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
        <script>
            $(document).ready(function () {
                $('#dataTables-example').DataTable({
                    responsive: true
                });
            });
        </script>

    </div>



@endsection
