
{{--MAIN LOOP--}}


@foreach($locations as $location)

                    @foreach($location->device as $device)
                        @if( ($device->ping != "1") and ($device->devicetype_id == "4"))
                            <ul>
                                <li>
                                    <a href="/location/{!! $location->id !!}">
                                        {!! $location->name !!}
                                    </a>
                                </li>

                                        @php
                                            $seconds = strtotime("now")-strtotime($device->lastseen);
                                        @endphp
                                        @if ($seconds > 86400)
                                            <ul>
                                              <li>
                                                  <i style="color:red" class="fa fa-bolt"></i>
                                                  <a href="/device/{!! $device->id !!}">
                                                      PM Down for {{gmdate("d \d H:i:s", $seconds)}}
                                                  </a>
                                              </li>
                                            </ul>

                                        @else
                                            <ul>
                                                <li>
                                                    <i style="color:red" class="fa fa-bolt"></i>
                                                    <a href="/device/{!! $device->id !!}">
                                                        PM Down for {{gmdate("H:i:s", $seconds)}}
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
                                    <ul>
                                        <li>
                                            @if ($location->standbytime > 1)
                                                {!! $location->standbytime !!} hrs on backup
                                            @else
                                                {!! ($location->standbytime * 60) !!} mins on backup
                                            @endif
                                        </li>
                                        <li>
                                            {!! $location->device->sum('active_pppoe') !!} Clients possibly with no power
                                        </li>
                                    </ul>
                            </ul>

                        @endif
                    @endforeach
@endforeach
<div class="panel panel-default" >
<div class="panel-heading">
    <b>
        Voltage Status
    </b>
    <a  href="/voltages/showall" target="_blank" class="btn btn-default">Show All Voltage Nodes</a>

</div>

    <ul>
    @if(isset($power_devices))
        @foreach($power_devices as $power_device)
            @if($power_device->volts + $power_device->getVoltageThreshold($power_device->voltage_offset) <= ($power_device->getVoltageThreshold($power_device->voltage_threshold)))
            <li>
                {!! $power_device->name !!} :  {!! ($power_device->volts + $power_device->getVoltageThreshold($power_device->voltage_offset)) !!} V {!! $power_device->voltage_seen_at !!}

            </li>
            @endif
        @endforeach
    @endif
    </ul>
</div>
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

