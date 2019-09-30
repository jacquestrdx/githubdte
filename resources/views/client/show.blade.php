
@extends('layouts.app')

@section('content')
    <div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">Device basic info</div>
        <div class="panel-body">

            <table class="table hover">
                <tr>
                    <td>Device Name</td>
                    <td>{{$client->name}}</td>
                </tr>
                <tr>
                    <td>Device IP</td>
                    <td>
                        <a href="http://{{$client->ip}}" target="_blank">{{$client->ip}}</a>
                    </td>
                </tr>
                <tr>
                    <td>Device Type</td>
                    <td>{{$client->devicetype->name}}</td>
                </tr>
                <tr>
                    <td>
                        Client Reseller
                    </td>
                    <td>
                        {!! $client->reseller !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        Location
                    </td>
                    <td>
                        <a href="{{ route('location.show', $client->location->id) }}">
                            {{$client->location->name or ""}}
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        Last seen
                    </td>
                    <td>
                        {{$client->lastseen}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Last down
                    </td>
                    <td>
                        {{$client->lastdown}}
                    </td>
                </tr>

                <tr>
                    <td>Ping Status</td>
                    @if($client->ping==1)
                        <td style="color:green">
                            Up
                            @if ($client->ping1 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endif
                            @if ($client->ping2 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endif
                            @if ($client->ping3 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endif
                        </td>
                    @else
                        <td style="color:red">
                            Down
                            @if ($client->ping1 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endif
                            @if ($client->ping2 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endif
                            @if ($client->ping3 == 1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i>
                    @endif

                    @endif
                </tr>

                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel">
            <div class="panel-heading">Client Pings</div>
                <div class="panel-body">
                    <div id="client_ping_chart" style="max-height:200px;margin-top:100px;max-width: 90%;margin-left:5%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel">
            <div class="panel-heading">Client Traffic</div>
            <div class="panel-body">
                <div style='max-height:200px;margin-top:100px;max-width: 90%;margin-left:5%' id="traffic_graph">
            </div>
        </div>
    </div>


    @push('scripts')
                <script>

                    $(document).ready(function(){
                        showPingStats();
                        showGraph();
                    });


                    function showGraph(){
                        $.ajax({
                            url: "{{config('url.root_url')}}/getClientTraffic/{{$client->id}}",                  //the script to call to get data
                            data: "",                        //you can insert url arguments here to pass to api.php
                                                             //for example "id=5&parent=6"
                            dataType: 'json',                //data format
                            success: function (data2) {
                                console.log(data2);
                                new Morris.Area({
                                    // ID of the element in which to draw the chart.
                                    element: 'traffic_graph',
                                    // Chart data records -- each entry in this array corresponds to a point on
                                    // the chart.
                                    data: data2,
                                    // The name of the data record attribute that contains x-values.
                                    xkey: 'time',
                                    // A list of names of data record attributes that contain y-values.
                                    ykeys: ['txvalue', 'rxvalue'],
                                    // Labels for the ykeys -- will be displayed when you hover over the
                                    // chart.
                                    labels: ['Download', 'Upload'],
                                    pointSize: 0,
                                    resize: true
                                });
                            }
                        });
                    }
                    function showPingStats() {

                        $.ajax({
                            url: "{{config('url.root_url')}}/getClientPings/{{$client->id}}",                  //the script to call to get data
                            data: "",                        //you can insert url arguments here to pass to api.php
                                                             //for example "id=5&parent=6"
                            dataType: 'json',                //data format
                            success: function (data2)          //on receive of reply
                            {
                                new Morris.Area({
                                    // ID of the element in which to draw the chart.
                                    element: 'client_ping_chart',
                                    // Chart data records -- each entry in this array corresponds to a point on
                                    // the chart.
                                    data: data2,
                                    // The name of the data record attribute that contains x-values.
                                    xkey: 'year',
                                    // A list of names of data record attributes that contain y-values.
                                    ykeys: ['value'],
                                    pointSize: 0,
                                    // Labels for the ykeys -- will be displayed when you hover over the
                                    // chart.
                                    labels: ['Ping in ms']
                                });
                            },
                            error: function () {
                                alert('Could not pull graph pings');
                            }
                        });
                    };
                </script>
@endpush()




            @push('head')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

                @endpush

@endsection
