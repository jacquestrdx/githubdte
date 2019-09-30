@extends('layouts.app')

@section('content')
@push('head')

@endpush
<div id="foo"></div>
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">PPPOE info
            </div>

@if (isset($array))
@foreach ($array as $key => $row)
   <div class='row'>
       <h4>
               {!! $key !!}
       </h4>
   </div>

    <div style='max-height:200px;margin-left:20px;margin-right:20px' class='col-md2' id={{$key}}>

   </div>

@endforeach
@endif


</div>
</div>
</div>
@endsection

@push('scripts')
        @if (isset($array))
            @foreach ($array as $key => $row)

                <script>

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
                            hideHover: "true",                        // The name of the data record attribute that contains x-values.
                            xkey: 'time',
                            // A list of names of data record attributes that contain y-values.
                            ykeys: ['value'],
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