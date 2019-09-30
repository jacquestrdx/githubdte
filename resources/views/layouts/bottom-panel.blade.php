
    <div class="panel panel-default col-md-6 col-md-offset-0">
    <div class="panel-heading" style="font-weight: bold;">Down Links</div>
    <div class="panel-body">
            @foreach ($bgppeers as $bgppeer)
                @if( ($bgppeer->state != 'established') AND ($bgppeer->acknowledged != '1') and ($bgppeer->disabled == 'false') )
                            <ul>
                                <li>
                                    <a href="{{ route('device.show',$bgppeer->device->id) }}">{{$bgppeer->device->name}}
                                        ({{$bgppeer->device->ip}})</a> to
                                    @if ($instantdevice->getASNDeviceName($bgppeer->remote_as) =="Unknown")
                                        {{$bgppeer->name}}({{$bgppeer->remote_address}})
                                    @else
                                        <a href="{{ route('device.show',$instantdevice->getASNDeviceID($bgppeer->remote_as)) }}">
                                            {{$instantdevice->getASNDeviceName($bgppeer->remote_as)}} ( {{$bgppeer->remote_address}} )
                                        <a>
                                    @endif
                                </li>
                            </ul>
                @endif
            @endforeach

        </div>
    </div>

        <div style="max-width: 49%;margin-left: 1%;" class="panel panel-default col-md-6">
            <div class="panel-heading" style="font-weight: bold;">Down Cameras</div>
            <div class="panel-body">
                @foreach ($devices as $device)
                    @if(($device->ping !="1") AND ($device->devicetype_id =="16"))
                        <ul>
                            <li>
                                <a href="{{ route('device.show',$device->id) }}">
                                    {{$device->name}}
                                </a>
                            </li>
                        </ul>
                    @endif
                @endforeach

            </div>
        </div>

    </div>




