<div class="panel panel-default">
    <div class="panel-heading">Device basic info</div>
    <div class="panel-body">

        <table class="table hover">
            <tr>
                <td>Device Name</td>
                <td>
                    {{$device->name}}
                    <a style="float: right" class="btn btn-default" target="_blank" href="{{ route('device.mikrotik.interfaces',$device->id) }}">
                        Device Interfaces
                    </a>
                    {{----}}
                    <a style="float: right" class="btn btn-default" target="_blank" href='{{ url("/mikrotik/graph/$device->id") }}'>
                        Force Graph Pull
                    </a>
                    @if($device->devicetype_id=="29")
                        <a style="float: right" class="btn btn-default"  href="{{ url("/statables/graphs/$device->id") }}">
                            Device Stations
                        </a>
                    @endif
                    <a style="float: right" class="btn btn-default" target="_blank" href="{{ route('device.graphs',$device->id) }}">
                        Device Stats
                    </a>
                    <a style="float: right" class="btn btn-default" target="_blank" href="/showdevice/pings/{!! $device->id !!}">
                        Device Pings
                    </a>
                    <a style="float: right" class="confirm-test btn btn-default" href="/test/device/{!! $device->id !!}">
                    Flood test
                    </a>
                    <a style="float: right" class="btn btn-default" href="/customsnmpoid/{!! $device->id !!}">
                        Custom Oid's
                    </a>
                </td>
            </tr>
            <tr>
                <td>Device IP</td>
                <td>
                    <a href="http://{{$device->ip}}" target="_blank">{{$device->ip}}</a>
                </td>
            </tr>
            <tr>
                <td>Device Uptime</td>
                <td>
                    @if ($device->uptime > 86400)
                        {{gmdate("d \d H:i:s", $device->uptime)}}
                    @else
                        {{gmdate("H:i:s", $device->uptime)}}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    Perform Traceroute
                </td>
                <td>
                    <a href="/traceroute/{{$device->id}}" target="_blank">Traceroute</a>
                </td>
            </tr>
            <tr>
                <td>Device Type</td>
                <td>
                    {{$device->devicetype->name or ""}}
                </td>
            </tr>
            <tr>
                <td>
                    Location
                </td>
                <td>
                    <a href="{{ route('location.show', $device->location_id) }}">
                        {{$device->location->name or ""}}
                    </a>
                </td>
            </tr>

            <tr>
                <td>
                    Last seen
                </td>
                <td>
                    {{$device->lastseen}}
                </td>
            </tr>

            <tr>
                <td>
                    Last down
                </td>
                <td>
                    {{$device->lastdown}}
                </td>
            </tr>

            <tr>
                <td>
                    Added
                </td>
                <td>
                    {{$device->created_at}}
                </td>
            </tr>
            @if( ($device->devicetype_id == 2) OR ($device->devicetype_id == 22) OR ($device->devicetype_id == 17))
                <tr>
                    <td>Last Flood test</td>
                    <td>Down: {!! $device->last_download_test !!} Mbps  / Up: {!! $device->last_upload_test !!} Mbps</td>
                </tr>
                <tr>
                    <td>Flood test time</td>
                    <td>{!! $device->last_speed_time !!}</td>
                </tr>
            @endif

            <tr>
                <td>Ping Status</td>
                @if($device->ping==1)
                    <td style="color:green">
                        Up
                        @if ($device->ping1 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping2 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping3 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping4 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                    </td>
                @else
                    <td style="color:red">
                        Down
                        @if ($device->ping1 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping2 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping3 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                        @if ($device->ping4 == 1)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                @endif

                @endif
            </tr>
            <tr>
                <td> Device Serial</td>
                <td> {{$device->serial_no}}</td>
            </tr>

            <tr>
                <td>
                    Device License
                </td>
                <td>
                    {!! $device->license_1 !!}
                </td>
            </tr>
            <tr>
                <td>
                    Device License
                </td>
                <td>
                    {!! $device->license_2 !!}
                </td>
            </tr>

            <!-- Trigger/Open The Modal -->

            <!-- The Modal -->


            <tr>
            </tr>
        </table>

        <div class="chart">
            @if(isset($ping_chart))
                {!! $ping_chart->container() !!}
            @endif

        </div>
        <div>
            @if(isset($availibilty_ping_chart))
                {!! $availibilty_ping_chart->container() !!}
            @endif
        </div>
        @push('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
            @if(isset($ping_chart_render))
                @if($ping_chart_render)
                    {!! $ping_chart->script() !!}
                @endif
            @endif
            @if(isset($availibilty_ping_chart_render))
                @if($availibilty_ping_chart_render)
                    {!! $availibilty_ping_chart->script() !!}
                @endif
            @endif
        @endpush



    </div>

    </table>
</div>
</div>