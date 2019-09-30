@extends('layouts.app')

@section('title', 'Show Device')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">


                    <div class="panel-heading">
                        <strong>{{$device->name}}</strong>
                        @if (\Auth::user()->user_type=="admin")
                            <a class="confirm" style="color:darkred;float:right" href="/device/destroy/{{$device->id}}">Delete
                                <span class="btn btn-danger btn-sm" title="Delete">
                                        <span style="color:red" class="fa fa-minus-circle "></span></span>
                            </a>

                            <a style="float:right" href="/customsnmpoids/create/{!! $device->id !!}">Add a custom SNMP OID
                                <span class="btn btn-create btn-sm" title="Delete">
                                <span style="color:dodgerblue" class="fa fa-plus-circle "></span></span>
                            </a>

                            </br>
                        @endif
                    </div>

                    <div class="panel-body">

                        @include('device.basics')

                        <div class="panel panel-default">
                            <div class="panel-heading">

                            </div>
                            <div class="panel-body">

                                @if (($device->devicetype_id=="1") or ($device->devicetype_id=="15") or ($device->devicetype_id=="26") or ($device->devicetype_id=="6"))
                                    @include('device.mikrotik.show')
                                @endif

                                @if (($device->devicetype_id=="2") OR ($device->devicetype_id=="10") OR ($device->devicetype_id=="22") OR ($device->devicetype_id=="11"))
                                    @include('device.ubnt.show')
                                @endif

                                @if (($device->devicetype_id=="2")  OR ($device->devicetype_id=="22")
                                        OR ($device->devicetype_id=="17") OR ($device->devicetype_id=="15") )
                                    @include('device.sectorpppoe.show')
                                @endif

                                @if (($device->devicetype_id=="17"))
                                    @include('device.cambium.show')
                                @endif

                                @if (($device->devicetype_id=="14"))
                                    @include('device.airfibre.show')
                                @endif

                                @if ( ($device->devicetype_id=="8") OR ($device->devicetype_id=="19"))
                                    @include('device.ligowave.show')
                                @endif

                                @if ($device->devicetype_id =="20")
                                    @include('device.smtp.show')
                                @endif

                                @if ($device->devicetype_id =="28")
                                    @include('device.micro.show')
                                @endif

                                @if ($device->devicetype_id =="15")
                                    @include('device.mikrotik.wireless')
                                @endif

                                @include('device.buttons')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-0">
                    <div class="panel panel-default">
                        <div class="panel-heading">Additional info</div>
                            <div>
                                @if ($device->devicetype_id =="1")
                                    @include('device.mikrotik.bgppeers')
                                @endif
                                @if ($device->devicetype_id =="6")
                                    @include('device.mikrotik.bgppeers')
                                @endif

                                @if ($device->devicetype_id =="1")
                                    @include('device.mikrotik.active_pppoe')
                                @endif
                                @if ($device->devicetype_id =="5")
                                    @include('device.siae.show')
                                @endif
                                @if ( ($device->devicetype_id =="2") OR ($device->devicetype_id =="17"))
                                    @include('device.ubnt.statable')
                                @endif

                                @if ($device->devicetype_id =="22")
                                    @include('device.ubnt.statable')
                                @endif
                                @if ($device->devicetype_id =="29")
                                    @include('device.intracom.statable')
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
</div>


    @push('scripts')
        {{--<script>--}}
            {{--$(document).ready(function(){--}}
                {{--getStats();--}}
            {{--});--}}
            {{--function getStats() {--}}
                {{--$.ajax({--}}
                    {{--url: "../getDevicePings/{{$device->id}}",                  //the script to call to get data--}}
                    {{--data: "",                        //you can insert url arguments here to pass to api.php--}}
                                                     {{--//for example "id=5&parent=6"--}}
                    {{--dataType: 'json',                //data format--}}
                    {{--success: function(data2)          //on receive of reply--}}
                    {{--{--}}
                        {{--new Morris.Area({--}}
                            {{--// ID of the element in which to draw the chart.--}}
                            {{--element: 'myfirstchart',--}}
                            {{--// Chart data records -- each entry in this array corresponds to a point on--}}
                            {{--// the chart.--}}
                            {{--data:  data2 ,--}}
                            {{--// The name of the data record attribute that contains x-values.--}}
                            {{--xkey: 'year',--}}
                            {{--// A list of names of data record attributes that contain y-values.--}}
                            {{--ykeys: ['value'],--}}
                            {{--pointSize : 0,--}}
                            {{--// Labels for the ykeys -- will be displayed when you hover over the--}}
                            {{--// chart.--}}
                            {{--labels: ['Ping in ms']--}}
                        {{--});--}}
                    {{--},--}}
                    {{--error: function(){--}}
                        {{--alert('Could not pull graph pings');--}}
                    {{--}--}}
                {{--});--}}
            {{--}--}}
        {{--</script>--}}
    @endpush
@endsection