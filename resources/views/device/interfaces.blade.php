
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All Devices

                    </div>

                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Interface Name</th>
                                    <th>Interface Speed</th>
                                    <th>Interface Status</th>
                                    <th>Admin Status</th>
                                    <th>Rx Speed</th>
                                    <th>Tx Speed </th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($interfaces as $interface)
                                    <tr>
                                        <td>{!! $interface->id !!}</td>
                                        <td><a href='/dinterface/{!! $interface->id !!}'>{!! $interface->name  !!}</a></td>
                                        <td>{!! $interface->speed !!}</td>
                                        <td>{!! $interface->running !!}</td>
                                        <td>{!! $interface->disabled !!}</td>
                                        <td>{!! $interface->rxspeed !!}</td>
                                        <td>{!! $interface->txspeed !!}</td>
                                        <td style="color:red"><a href="/interfaces/delete/{!! $interface->id !!}">Delete</a></td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
