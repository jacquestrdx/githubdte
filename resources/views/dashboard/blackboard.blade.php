<html>
<head>
    <style>
        .floating-menu {
            font-family: sans-serif;
            background: black;
            padding: 5px;;
            width: 100%;
            z-index: 100;
            position: fixed;
        }
        .floating-menu a,
        .floating-menu h3 {
            font-size: 0.9em;
            display: block;
            margin: 0 0.5em;
            color: white;
        }
        *{
            margin:0;
            padding:0;
        }
        h1{
            color:whitesmoke
        }
        a:active {
            color: yellow;
        }
        body{
            font-family:arial,sans-serif;
            font-size:80%;
            background:#666;
            color:#fff;
        }
        p {
            margin: 0;
            padding: 0;
        }

        h2,p{
            font-size:100%;
            font-weight:normal;
        }
        ul,li{
            list-style:none;
        }
        ul{
            overflow:hidden;
            padding:3em;
        }
        ul li a{
            text-decoration:none;
            color : black;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        ul li{
            margin:1em;
            float:left;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body style="background: #0c0c0c">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
<nav class="floating-menu">
    <div class="row">
        <div class="col-3"><a href="#power">Down Power Monitors</a></div>
        <div class="col-3"><a href="#devices">Down Devices</a></div>
        <div class="col-3"><a href="#interfaces">Interface Changes</a></div>
        <div class="col-3"><a href="#interfacewarnings">Interface Warnings</a></div>
        <div class="col-3"><a href="#faults">Faults</a></div>
        <div class="col-3"><a href="#bgp">BGP Peers</a></div>
    </div>
</nav>
<div style="height: 70px"></div>
    <h1 style="color:whitesmoke">Down Power Monitors</h1>
    @if(sizeof($powermonitors) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No Sites without power</h3></p>
    @else
        <div class="row" id="power">
            @foreach ($powermonitors as $device)
                    @if( ( (time() - strtotime($device->lastdown))/60/60 ) < ($device->location->standbytime-2) )
                    <div style="background: orange;border-color: black;border-style:solid" class="col-3">
                        <p style="padding-top: 5px">
                            <a href="/device/{!! $device->id !!}">
                                {!! $device->location->name !!} Power Monitor
                            </a>
                        </p>
                        <p>Down since : {!! $device->lastdown !!}</p>
                        @php
                            $seconds = strtotime("now")-strtotime($device->lastseen);
                        @endphp
                        @if ($seconds > 86400)
                            <p>Down for : {!! gmdate("d \d H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                        @else
                            <p>Down for : {!! gmdate("H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                        @endif
                        <p>
                            Time left: @php echo round(($device->location->standbytime - (time() - strtotime($device->lastdown))/60/60),0) @endphp hrs
                        </p>
                    </div>
                @else
                    <div style="background: red;border-color: black;border-style:solid" class="col-3">
                        <p style="padding-top: 5px">
                            <a href="/device/{!! $device->id !!}">
                                {!! $device->location->name !!} Power Monitor
                            </a>
                        </p>
                        <p>Down since : {!! $device->lastdown !!}</p>
                        @php
                            $seconds = strtotime("now")-strtotime($device->lastseen);
                        @endphp
                        @if ($seconds > 86400)
                            <p>Down for : {!! gmdate("d \d H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                        @else
                            <p>Down for : {!! gmdate("H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                        @endif
                        <p>
                            Time left: @php echo round(($device->location->standbytime - (time() - strtotime($device->lastdown))/60/60),0) @endphp hrs
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    <h1 style="color:whitesmoke">Down Devices</h1>
    @if(sizeof($devices) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No devices down</h3></p>
    @else
        <div class="row" id="devices">
    @foreach ($devices as $device)
        @if( time() - strtotime($device->lastdown) > 3600)
            <div style="background: red;border-color: black;border-style:solid" class="col-3">
                <p style="padding-top: 5px">
                    <a href="/device/{!! $device->id !!}">
                        {!! $device->name !!}
                    </a>
                </p>
                <p>Down since : {!! $device->lastdown !!}</p>
                <p>Down for : {!! gmdate("d \d H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                <p>Downs Today: {!! $device->downs_today !!}</p>
            </div>
        @else
            <div style="background: darkorange;border-color: black;border-style:solid" class="col-3">
                <p style="padding-top: 5px">
                    <a href="/device/{!! $device->id !!}">
                        {!! $device->name !!}
                    </a>
                </p>
                <p>Down since : {!! $device->lastdown !!}</p>
                <p>Down for : {!! gmdate("H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                <p>Downs Today: {!! $device->downs_today !!}</p>

            </div>
        @endif
        @endforeach
        @foreach ($up_devices as $device)
                <div style="background: green;border-color: black;border-style:solid" class="col-3">
                    <p style="padding-top: 5px">
                        <a href="/device/{!! $device->id !!}">
                            {!! $device->name !!}
                        </a>
                    </p>
                    <p>Up since : {!! $device->lastseen !!}</p>
                    <p>Downs Today: {!! $device->downs_today !!}</p>
                </div>
        @endforeach
        </div>
    @endif
    <h1 style="color:whitesmoke">interface changes</h1>
    @if(sizeof($interfacelogs) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No Sites without power</h3></p>
    @else
        <div class="row" id="interfaces">
            @foreach ($interfacelogs as $interface => $interfacelog)
                <div style="background: orange;border-color: black;border-style:solid" class="col-3">
                    <p>
                        <a href="/dinterface/{!! $interface !!}">
                            {!! $instantinterface->getDeviceName($interface) !!}
                            --
                            {!! $instantinterface->getInterfaceName($interface) !!}
                        </a>
                    </p>
                    <p>
                        {!! sizeof($interfacelog) !!} events today
                    </p>
                </div>
            @endforeach
        </div>
    @endif
    <h1 style="color:whitesmoke" id="interfacewarnings"> Interface threshold Warnings</h1>
    @if(sizeof($interfacewarnings) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No interfaces close to thresholds</h3></p>
    @else
        <div class="row">
    @foreach ($interfacewarnings as $interface => $interfacewarning)
        <div style="background: orange;border-color: black;border-style:solid" class="col-3">
            <p>
                <a href="/dinterface/{!! $interface !!}">
                    {!! $instantinterface->getDeviceName($interface) !!}
                    --
                    {!! $instantinterface->getInterfaceName($interface) !!}
                </a>
            </p>
            <p>
                {!! sizeof($interfacewarning) !!} threshold events today
            </p>

        </div>
    @endforeach
        </div>
    @endif
    <h1 style="color:whitesmoke"> Device Faults</h1>
    @if(sizeof($faults) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No device Faults</h3></p>
    @else
        <div class="row" id="faults">
        @foreach ($faults as $fault)
            @if($fault->status == "1")
            <div style="background: darkorange;border-color: black;border-style:solid" class="col-3">
                <p style="padding-top: 5px">
                    <a href="/device/{!! $fault->device->id !!}">
                        {!! $fault->device->ip !!} -- {!! $fault->device->name !!} {!! $fault->id !!}

                    </a>
                </p>
                <p>{!! $fault->description !!}</p>
                <p>{!! $fault->created_at !!}</p>
            </div>
            @else
                @if(NULL!=strpos($fault->description,'Cpu'))
                    <div style="background: green;border-color: black;border-style:solid" class="col-3">
                        <p style="padding-top: 5px">
                            <a href="/device/{!! $fault->device->id !!}">
                                {!! $fault->device->ip !!} -- {!! $fault->device->name !!}  {!! $fault->id !!}

                            </a>
                        </p>
                        <p>Device Cpu now at {!! $fault->device->cpu !!} %</p>
                        <p>{!! $fault->description !!}</p>
                        <p> Lasted from {!! $fault->created_at !!} to {!! $fault->updated_at !!}  </p>
                    </div>
                @elseif(NULL!=strpos($fault->description,'memory'))
                    <div style="background: green;border-color: black;border-style:solid" class="col-3">
                        <p style="padding-top: 5px">
                            <a href="/device/{!! $fault->device->id !!}">
                                {!! $fault->device->ip !!} -- {!! $fault->device->name !!}  {!! $fault->id !!}

                            </a>
                        </p>
                        <p>Device Memory is now at {!! $fault->device->used_memory !!} %</p>
                        <p>{!! $fault->description !!}</p>
                        <p> Lasted from {!! $fault->created_at !!} to {!! $fault->updated_at !!}  </p>
                    </div>
                @else
                    <div style="background: green;border-color: black;border-style:solid" class="col-3">
                        <p style="padding-top: 5px">
                            <a href="/device/{!! $fault->device->id !!}">
                                {!! $fault->device->ip !!} -- {!! $fault->device->name !!}  {!! $fault->id !!}

                            </a>
                        </p>
                        <p>{!! $fault->description !!}</p>
                        <p> Lasted from {!! $fault->created_at !!} to {!! $fault->updated_at !!}  </p>
                    </div>
                @endif
            @endif
    @endforeach
        </div>
    @endif
    <h1 style="color:whitesmoke"> Down BGP Peers</h1>
    @if(sizeof($faults) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No down BGP Peers</h3></p>
    @else

    <div class="row" id="bgp">
    @foreach ($bgppeers as $bgppeer)
            <div style="background: darkorange;border-color: black;border-style:solid" class="col-3">
                <p style="padding-top: 5px">
                <h2>
                    <a href="/device/{!! $bgppeer->device_id !!}">
                        {!! $bgppeer->device->name !!}
                    </a>
                </h2>
                </p>
                <p>{!! $bgppeer->name !!}</p>
                <p>{!! $bgppeer->remote_address !!}</p>
                <p>{!! $bgppeer->updated_at !!}</p>
            </div>
    @endforeach
    </div>
    @endif

</body>
<script>
    window.setInterval(function(){
        location.reload();
    }, 30000);

</script>

</html>