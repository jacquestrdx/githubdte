<html>
<head>
    <style>
        *{
            margin:0;
            padding:0;
        }
        h1{
            color:whitesmoke
        }
        body{
            font-family:arial,sans-serif;
            font-size:100%;
            margin:3em;
            background:#666;
            color:#fff;
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
        .li5{
            text-decoration:none;
            background:red;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        .li4{
            text-decoration:none;
            background:darkred;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        .li3{
            text-decoration:none;
            background:mediumvioletred;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        .li2{
            text-decoration:none;
            background:indianred;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        .li1{
            text-decoration:none;
            background:orangered;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
        }
        .liack{
            text-decoration:none;
            background:cornflowerblue;
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
    <h1 style="color:whitesmoke">Down Power Monitors</h1>
    @if(sizeof($powermonitors) <=0)
        <p style="color:green"><h3 style="color:green;padding:1em;">No Sites without power</h3></p>
    @else
        <div class="row">
            @foreach ($powermonitors as $device)
                    @if( ( (time() - strtotime($device->lastdown))/60/60 ) < ($device->location->standbytime-2) )
                    <div style="background: orange;border-color: black;border-style:solid" class="col-3">
                        <p>
                            <a href="/device/{!! $device->id !!}">
                                {!! $device->location->name !!}
                            </a>
                        </p>
                        <p>Down since : {!! $device->lastdown !!}</p>
                        <p>
                            Time left: @php echo round(($device->location->standbytime - (time() - strtotime($device->lastdown))/60/60),0) @endphp hrs
                        </p>
                    </div>
                @else
                    <div style="background: red;border-color: black;border-style:solid" class="col-3">
                        <p>
                            <a href="/device/{!! $device->id !!}">
                                {!! $device->location->name !!}
                            </a>
                        </p>
                        <p>Down since : {!! $device->lastdown !!}</p>
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
        <div class="row">
    @foreach ($devices as $device)
        @if( time() - strtotime($device->lastdown) > 3600)
            <div style="background: red;border-color: black;border-style:solid" class="col-3">
                <p>
                    <a href="/device/{!! $device->id !!}">
                        {!! $device->name !!}
                    </a>
                </p>
                <p>Down since : {!! $device->lastdown !!}</p>
                <p>Down for : {!! gmdate("H:i:s",time()-strtotime($device->lastdown)) !!}</p>
                <p>Downs Today: {!! $device->downs_today !!}</p>
            </div>
        @else
            <div style="background: darkorange;border-color: black;border-style:solid" class="col-3">
                <p>
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
        </div>
    @endif
    {{--<h1 style="color:whitesmoke">interface changes</h1>--}}
    {{--@if(sizeof($interfacelogs) <=0)--}}
        {{--<p style="color:green"><h3 style="color:green;padding:1em;">No Sites without power</h3></p>--}}
    {{--@else--}}
        {{--<div class="row">--}}
            {{--@foreach ($interfacelogs as $interfacelog)--}}
                    {{--<div style="background: orange;border-color: black;border-style:solid" class="col-3">--}}
                        {{--<p>--}}
                            {{--<a href="/device/{!! $interfacelog->device_id !!}">--}}
                                {{--{!! $interfacelog->device->name !!}--}}
                            {{--</a>--}}
                        {{--</p>--}}
                        {{--<p>Status : {!! $interfacelog->status !!}</p>--}}
                        {{--<p>Time : {!! $interfacelog->created_at !!}</p>--}}
                    {{--</div>--}}
            {{--@endforeach--}}
        {{--</div>--}}
    {{--@endif--}}



</body>
<script>
    window.setInterval(function(){
        location.reload();
    }, 30000);

</script>

</html>