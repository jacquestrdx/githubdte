<div class="panel-heading" style="font-weight: bold;">Down devices</div>
{{--MAIN LOOP--}}
    @foreach($locations as $location)
            @if ($location->status!="0")
                @if ($location->getDownCount($location) == count($location->device))
                    <div style="margin-left:5%;margin-top:1%;font-weight: bold ;">
                        <a href="{{ route('location.show',$location->id) }}">
                            {{$location->name}}
                        </a>
                        <div style="float:right;margin-right: 5%;">{{$location->getDownCount($location)}} / {{count($location->device)}}</div>
                    </div>
                    <ul>
                        {{--FOREACH DEVICE--}}
                        @foreach($location->device as $device)
                            @php
                                $seconds = strtotime("now")-strtotime($device->lastseen);
                            @endphp
                            @if ($device->devicetype_id == "4")
                                @if ($seconds > 86400)
                                    <li>PM Down for {{gmdate("d \d H:i:s", $seconds)}}</li>
                                    <li>
                                        {{$location->standbytime}} hrs on battery standby
                                    </li>
                                @else
                                    <li>PM Down for  {{gmdate("H:i:s", $seconds)}} </li>
                                    <li>
                                        {{$location->standbytime}} hrs on battery standby
                                    </li>
                                @endif
                            @else
                            @endif
                        @endforeach
                        {{--ENDFOREACH DEVICE--}}
                        <li>Complete HS down for {{$location->getAverageDowntime($location)}}</li>
                    </ul>
                @else
                    {{--IF WHOLE HS NOT OFFLINE--}}
                    <div style="margin-left:5%;margin-top:1%;font-weight: bold ;">
                        <a href="{{ route('location.show',$location->id) }}">
                            {{$location->name}}
                        </a>
                        <div style="float:right;margin-right: 5%;">{{$location->getDownCount($location)}} / {{count($location->device)}}</div>
                    </div>
                    <ul>
                @endif
            @endif
        @if ($location->getDownCount($location) == count($location->device))

        @else

            @foreach ($location->device as $device)
                @if(($device->ping!="1") AND ($device->devicetype_id != "16"))
                <li style="margin-left:5%;">
                    @include('layouts.icon')
                    <a href="{{ route('device.show',$device->id) }}">
                        {{$device->name}}
                    </a>
                    @if($device->devicetype_id =="4")
                    - {{$location->standbytime}} hrs on battery standby
                    @endif
                    @php
                        $seconds = strtotime("now")-strtotime($device->lastseen);
                    @endphp
                </li>

                        <div style="font-size:80%; margin-left:8%">
                            @if ($seconds > 86400)
                                Down for {{gmdate("d \d H:i:s", $seconds)}}
                            @else
                                Down for {{gmdate("H:i:s", $seconds)}}
                            @endif
                            <div style="font-size:80%; margin-left:8%">{{$device->downs_today." time today"}}
                            @if ($device->acknowledged != "1")
                                <a href="{{ route('device.acknowledge',$device) }}">
                                    <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </a>
                            @else
                                <a href="{{ route('acknowledge.edit',$device->getAckID()) }}" title="{{$device->getAckUser() }}">
                                    <i class="fa fa-check" aria-hidden="true" ></i>
                                    <span class="sr-only">Loading...</span>
                                    {{$device->getAcknowledgementNote()}}
                                </a>
                            @endif
                            </div>

                        </div>
                @endif
        @endforeach
        @endif

    </ul>

    @endforeach
    @push('scripts')

    @endpush

                    {{--END MAIN LOOP--}}



                    {{--<div class="panel-heading" style="font-weight: bold;">Links Down</div>--}}
    {{--<div class="panel-body>--}}
    {{--@foreach($devices as $device)--}}
            {{--@foreach ($device->bgppeers as $bgppeer)--}}
                {{--@if($bgppeer->state !="established")--}}
                        {{--<li style="margin-left:5%;">--}}
                        {{--<a href="{{ route('device.show',$device->id) }}">Link between {{$device->name}} and {{$bgppeer->name}} is down</a>--}}
                    {{--</li>--}}
                {{--@endif--}}
            {{--@endforeach--}}
        {{--@endforeach--}}
    {{--</div>--}}