@extends('layouts.app')

@section('title', 'Show Device')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading"><strong>{{$device->name}}</strong></div>

                    <div class="panel-body">

                       <div id="graphcontainer">

                       </div>

                        <html>
                        <body>

                        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
                        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
                        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
                        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
                        @if (isset($array))
                            @foreach ($array as $key => $row)
                                <script>
                                    $("#graphcontainer").append("<div><h4>{!! $key !!}</h4></div>");
                                    $("#graphcontainer").append("<div style='max-height:200px;' class='col-md2' id={{$key}}></div>");

                                    getStats();

                                    function getStats() {
                                        data2 = "";
                                        data2 = {!! json_encode($row) !!};
                                        new Morris.Area({
                                            // ID of the element in which to draw the chart.
                                            element: '{!!$key !!}',
                                            // Chart data records -- each entry in this array corresponds to a point on
                                            // the chart.
                                            data:  data2 ,
                                            // The name of the data record attribute that contains x-values.
                                            xkey: 'time',
                                            // A list of names of data record attributes that contain y-values.
                                            ykeys: ['data'],
                                            // Labels for the ykeys -- will be displayed when you hover over the
                                            // chart.
                                            labels: [''],
                                            pointSize : 0,
                                            resize : true
                                        });
                                    }


                                </script>
                            @endforeach
                        @endif

                        </body>
                        </html>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
