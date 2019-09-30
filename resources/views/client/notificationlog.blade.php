@extends('layouts.app')

@section('title', 'Outages Log')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading">Outages Log</div>

                    <div class="panel-body">

                        <div class="row"></div>
                        <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Message</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($notifications as $notification)
                                        @if ($notification->type =="sound")
                                            <tr style="color:red">
                                                <td>{{$notification->client->name}}</td>
                                                <td>{{$notification->message}}</td>
                                                <td>{{$notification->updated_at}}</td>
                                            </tr>
                                        @else
                                            <tr style="color:green">
                                                <td>{{$notification->client->name}}</td>
                                                <td>{{$notification->message}}</td>
                                                <td>{{$notification->updated_at}}</td>
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
