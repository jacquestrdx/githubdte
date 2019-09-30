@extends('layouts.app')

@section('title', 'NMAP Subnets')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Nmap Subnets

                        <a style="float:right" href="{{ route('nmap.create') }}">
                            <span class="fa fa-plus-square"></span> Add
                        </a>

                    </div>

                    <div class="panel-body">

                        <table class="table table-striped table-bordered table-hover">

                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Subnet</th>
                                <th>Port 1</th>
                                <th>Port 2</th>
                                <th>Port 3</th>
                                <th>Port 4</th>
                                <th>Port 5</th>
                            </tr>
                            @foreach ($nmaps as $nmap)
                                <tr>
                                    <td>
                                        {!! $nmap->id !!}
                                    </td>
                                    <td>
                                        {!! $nmap->description !!}
                                    </td>
                                    <td>
                                        {!! $nmap->subnet !!}
                                    </td>
                                    <td>
                                        {!! $nmap->port_1 !!}
                                    </td>
                                    <td>
                                        {!! $nmap->port_2 !!}
                                    </td>
                                    <td>
                                        {!! $nmap->port_3 !!}
                                    </td>
                                    <td>
                                        {!! $nmap->port_4 !!}
                                    </td>
                                    <td>
                                        {!! $nmap->port_5 !!}
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
