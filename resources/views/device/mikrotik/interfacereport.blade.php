@extends('layouts.app')

@section('content')
    <h2>Min / Max for interfaces of this device</h2>
<div id="foo"></div>


    @push('scripts')
        @if (isset($array))
            @foreach ($array as $key => $row)
                <script>
                    $("#foo").append("<div><h4>{!! $key !!}</h4></div>");
                    $("#foo").append("<div style='max-height:200px;' class='col-md2' id={{$key}}></div>");
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
                            ykeys: ['maxtx','maxrx'],
                            // Labels for the ykeys -- will be displayed when you hover over the
                            // chart.
                            labels: ['TX','RX'],
                            pointSize : 0,
                            resize : true
                        });
                    }
                </script>
            @endforeach
        @endif
    @endpush
@endsection


