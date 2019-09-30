    <div>*Summary*</div>
    <div>
        Total down devices : {{$totaldowndevices}} </br>
        Total PPPOEs :{{$totalpppoe}}   </br>
        Power Monitors down :{{$powermonsdown}} </br>
    </div>
    <ul>
        <li>-- </li>
        <li>-- </li>
    </ul>
    <div class="panel-heading">*Power Summary*</div>
        @foreach($locations as $location)
                @foreach($location->device as $device)
                    @if( ($device->devicetype_id=="4") AND ($device->ping=="0"))
                        <ul>
                            <li>*{!! $location->name  !!}* power down </li>
                            @if($location->standbytime <= "0")
                                <li> -- UNKNOWN hrs standbytime </li>
                                @php
                                    $seconds = strtotime("now")-strtotime($device->lastseen);
                                @endphp
                                @if ($seconds > 86400)
                                    <li> ❌ Down for {{gmdate("d \d H:i:s", $seconds)}}❌</li>
                                @else
                                    <li> ❌ Down for {{gmdate("H:i:s", $seconds)}}❌</li>
                                @endif
                            @else
                                <li> -- *{!! $location->standbytime !!} hrs* standbytime ❌</li>
                                @php
                                    $seconds = strtotime("now")-strtotime($device->lastseen);
                                @endphp
                                @if ($seconds > 86400)
                                    <li> ❌ Down for {{gmdate("d \d H:i:s", $seconds)}} ❌</li>
                                @else
                                    <li> ❌ Down for {{gmdate("H:i:s", $seconds)}} ❌</li>
                                @endif
                            @endif
                        </ul>
                    @endif
                @endforeach
        @endforeach

    </div>
                        <ul>
                            <li>-- </li>
                            <li>-- </li>
                        </ul>
    <div class="panel-heading">*Highsite Summary*</div>
    @foreach($locations as $location)
            @if ($location->status!="0")
                @if ($location->getDownCount($location) == count($location->device))
                    <div style="margin-left:5%;margin-top:1%;font-weight: bold">
                        *{{$location->name}}*
                    </div>
                    <ul>
                        <li>
                            ❌ All Devices Down ❌
                        </li>
                        <li>
                            {!! $location->device->sum('active_pppoe') !!} Clients Down
                        </li>
                    </ul>
                    <ul>
                @else
                <div style="margin-left:5%;margin-top:1%;font-weight: bold">
                   *{{$location->name}}*
                </div>
                <ul>
                @endif
            @endif
        @if ($location->getDownCount($location) == count($location->device))
        @else

            @foreach ($location->device as $device)
                @if($device->devicetype_id=="4")
                @else
                @if($device->ping!="1")
                    <li style="margin-left:5%;">
                        <a href="{{ route('device.show',$device->id) }}">
                            ❌  {{$device->name}}  ❌
                        </a>
                            @php
                                $seconds = strtotime("now")-strtotime($device->lastseen);
                            @endphp
                            @if ($seconds > 86400)
                                Down for {{gmdate("d \d H:i:s", $seconds)}}
                            @else
                                Down for {{gmdate("H:i:s", $seconds)}}
                            @endif
                    </li>
                    @if( ($device->devicetype_id == "2") or ($device->devicetype_id == "15") or ($device->devicetype_id == "17") or ($device->devicetype_id == "22"))
                        <li>
                             -- *Approx stations* {{$device->active_stations}}
                        </li>
                    @endif
                    @if( ($device->devicetype_id == "1"))
                        <li>
                             -- *Approx PPPoe* {{$device->active_pppoe}}
                        </li>
                    @endif
                @endif
                @endif
            @endforeach

        @endif

    </ul>
    @endforeach

