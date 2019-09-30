@extends('layouts.app')

@section('title', 'High Site Report')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">High Site Report
                        <a style="float:right;" href="http://dte.bronbergwisp.co.za/downloadhighsitereport">
                            Get CSV
                        </a>
                    </div>
                    <div class="panel-body">
                        @foreach($array as $name => $row)
                            <h4 style="color:darkblue">{{$name}}</h4>
                                    <table class="table hover">
                                        <thead>
                                            <th>Device Type</th>
                                            <th>Device Total</th>
                                        </thead>
                                        @foreach ($row as $devicetype => $count)
                                            <tr>
                                                <td>{{$devicetype}}</td>
                                                <td>{{$count}}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                            @endforeach
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
