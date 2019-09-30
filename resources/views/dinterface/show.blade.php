@extends('layouts.app')

@section('content')



    @if (isset($message))
        <p style="color:green">{!! $message !!}</p>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>{{$dinterface->name}}</strong></div>

                    <div class="panel-body">
                        <table class="table hover">
                            <tr><td>Name</td><td>{{$dinterface->name}}</td></tr>
                            <tr><td>Default Name</td><td>{{$dinterface->default_name}}</td></tr>
                            <tr><td>Mac Address</td><td>{{$dinterface->mac_address}}</td></tr>
                            <tr><td>Threshold</td><td>{{$dinterface->threshhold}}</td></tr>
                            <tr><td>Type</td><td>{{$dinterface->type}}</td></tr>
                            <tr><td>Last Link Down</td><td>{{$dinterface->last_link_down_time}}</td></tr>
                            <tr><td>Last Link Up</td><td>{{$dinterface->last_link_up_time}}</td></tr>
                            <tr><td>MTU</td><td>{{$dinterface->mtu}}</td></tr>

                            <tr><td>Actual MTU</td><td>{{$dinterface->actual_mtu}}</td></tr>
                            <tr><td>Running</td><td>{{$dinterface->running}}</td></tr>
                            <tr><td>Disabled</td><td>{{$dinterface->disabled}}</td></tr>
                            <tr><td>Speed</td><td>{{ ($dinterface->link_speed)/1000000 }} </td></tr>
                            <tr><td>Device Name</td><td><a href="/device/{{$dinterface->device_id}}">{{App\Device::getNameFromID($dinterface->device_id)}}</a></td></tr>
                            <tr>
                                <td>
                                    <a href="{{ route('dinterface.edit',$dinterface->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                    </a>
                                </td>
                                <td>
                                </td>
                            </tr>

                        </table>


                    </div>
                </div>
            </div>
            </br>
            </br>
            </br>
            </br>




        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface Uptime %</strong></div>
                    <div class="chart">
                        @if(isset($interface_status_chart))
                            {!! $interface_status_chart->container() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface Throughput (Mbps)</strong></div>
                    <div class="chart">
                        @if(isset($interface_chart))
                            {!! $interface_chart->container() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($dinterface->device->devicetype_id=="1")
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface Throughput (PPS)</strong></div>
                    <div class="chart">
                        @if(isset($interface_packets_chart))
                            {!! $interface_packets_chart->container() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface Errors</strong></div>
                    <div class="chart">
                        @if(isset($interface_errors_chart))
                            {!! $interface_errors_chart->container() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface event Logs</strong></div>
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                        @foreach($interfacelogs as $interfacelog)
                            <tr>
                                <td>
                                    {!! $interfacelog->readableStatus() !!}
                                </td>
                                <td>
                                    {!! $interfacelog->created_at !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>Interface warning Logs</strong></div>
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                        @foreach($interfacewarnings as $interfacewarning)
                            <tr>
                                <td>
                                    {!! $interfacewarning->message !!} running {!! $interfacewarning->threshold !!} Mbps
                                </td>
                                <td>
                                    {!! $interfacewarning->created_at !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if(isset($interface_chart_render))
            @if($interface_chart_render)
                {!! $interface_chart->script() !!}
            @endif
        @endif
        @if(isset($interface_packets_chart_render))
            @if($dinterface->device->devicetype_id=="1")
                @if($interface_packets_chart_render)
                {!! $interface_packets_chart->script() !!}
                @endif
            @endif
        @endif
        @if(isset($interface_errors_chart_render))
            @if($interface_errors_chart_render)
                {!! $interface_errors_chart->script() !!}
            @endif
        @endif
        @if(isset($interface_status_chart_render))
            @if($interface_status_chart_render)
                {!! $interface_status_chart->script() !!}
            @endif
        @endif
    @endpush

@endsection

