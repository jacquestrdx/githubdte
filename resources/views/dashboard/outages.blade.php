
{{--MAIN LOOP--}}


    @foreach($locations as $location)
        @if($location->status != "0")
            @if($location->getDownCount($location) == sizeof($location->device))
                <ul>
                    <li>
                        <a href="/location/{!! $location->id !!}">
                            {!! $location->name !!}
                        </a>
                        @if ($location->acknowledged != "1")
                            <a href="{{ route('location.acknowledge',$location) }}">
                                <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </a>
                        @else
                            <a href="{{ route('acknowledge.edit',$location->getAckID()) }}" title="{{$location->getAckUser() ." - ". $location->getAckUpdateTime() }} ">
                                <i class="fa fa-check" aria-hidden="true" ></i>
                                <span class="sr-only">Loading...</span>
                                {{$location->getAcknowledgementNote()}}
                            </a>
                        @endif
                        <div style="float:right;margin-right: 5%;">
                            <b>
                                {{$location->getDownCount($location)}} / {{count($location->device)}}
                            </b>
                        </div>
                    </li>
                    <ul>
                        <li>
                            <i class="fa fa-warning"></i>
                            Complete HS Down for {!! $location->getAverageDowntime($location) !!}
                        </li>
                        <li>
                            {!! $location->device->sum('active_pppoe') !!} Clients Down
                        </li>
                        @if(!is_null($location->getPossibleBackhauls()))
                        <li>
                            Possible affected Sites
                            <ul>
                                @foreach($location->getPossibleBackhauls() as $backhaul )
                                    <li>{!! $backhaul->name !!}</li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                </ul>
            @else
                <ul>
                    <li>
                        <a href="/location/{!! $location->id !!}">
                            {!! $location->name !!}
                        </a>
                        @if ($location->acknowledged != "1")
                            <a href="{{ route('location.acknowledge',$location) }}">
                                <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </a>
                        @else
                            <a href="{{ route('acknowledge.edit',$location->getAckID()) }}" title="{{$location->getAckUser()." - ". $location->getAckUpdateTime()  }}">
                                <i class="fa fa-check" aria-hidden="true" ></i>
                                <span class="sr-only">Loading...</span>
                                {{$location->getAcknowledgementNote()}}
                            </a>
                        @endif
                        <div style="float:right;margin-right: 5%;">
                            <b>
                                {{$location->getDownCount($location)}} / {{count($location->device)}}
                            </b>
                        </div>
                    </li>

                    <ul>
                        @foreach($location->device as $device)
                            @if( ($device->ping != "1") and ($device->devicetype_id != "4") and ($device->devicetype_id != "16"))
                                <li>
                                    <a href="/device/{!! $device->id !!}">
                                        {!! $device->name !!}
                                    </a>
                                </li>
                            <ul>
                                <li>
                                    {{$device->downs_today." time today"}}
                                </li>
                            </ul>
                            @if( ($device->devicetype_id == "2") or ($device->devicetype_id == "15") or ($device->devicetype_id == "17") or ($device->devicetype_id == "22"))
                                    <ul>
                                        <li>
                                            Approx {{$device->active_stations}} stations
                                        </li>
                                    </ul>
                            @endif
                            @if( ($device->devicetype_id == "1"))
                                <ul>
                                    <li>
                                        Approx {{$device->active_pppoe}} PPPoes
                                    </li>
                                </ul>
                            @endif

                                @php
                                    $seconds = strtotime("now")-strtotime($device->lastseen);
                                @endphp
                                @if ($seconds > 86400)
                                    <ul>
                                        <li>
                                            Down for {{gmdate("d \d H:i:s", $seconds)}}
                                        </li>
                                    </ul>


                                @else
                                    <ul>
                                        <li>
                                            Down for {{gmdate("H:i:s", $seconds)}}
                                        </li>
                                    </ul>
                                @endif
                            @endif

                                @if ( ($device->ping != "1") and ($device->devicetype_id == "16"))
                                    @php
                                        $seconds = strtotime("now")-strtotime($device->lastseen);
                                    @endphp
                                    @if ($seconds > 86400)

                                        <li>
                                            <i class="fa fa-camera"></i>
                                            <a href="/device/{!! $device->id !!}">
                                                {!! $device->name !!}
                                                Camera down for {{gmdate("d \d H:i:s", $seconds)}}
                                            </a>
                                        </li>

                                    @else
                                        <li>
                                            <i class="fa fa-camera"></i>
                                            <a href="/device/{!! $device->id !!}">
                                                {!! $device->name !!}
                                                Camera down for {{gmdate("H:i:s", $seconds)}}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                        @endforeach
                    </ul>
                </ul>
            @endif
        @else

        @endif
    @endforeach

