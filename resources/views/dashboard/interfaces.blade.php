@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <canvas id="chart" width="1000" height="230">
@push('scripts')
    <script type="text/javascript" src="http://smoothiecharts.org/smoothie.js"></script><style></style>


    <script type="text/javascript">
        var random = new TimeSeries();

        function createTimeline(data) {
            var chart = new SmoothieChart({millisPerPixel:100});
            chart.addTimeSeries(random, { strokeStyle: 'rgba(0, 255, 0, 1)', fillStyle: 'rgba(0, 255, 0, 0.2)', lineWidth: 2 });
            chart.streamTo(document.getElementById("chart"), 1000);
        }
        window.onload = function() {
            $.getJSON("http://dte.bronbergwisp.co.za/interface/monitor/13755", function(data) {
                createTimeline(data);
                });
        }

        setInterval(function() {
            $.getJSON("http://dte.bronbergwisp.co.za/interface/monitor/13755", function(data) {
                random.append(new Date().getTime(), data['rx']);
            });
        }, 1000);


    </script>
@endpush

@endsection
