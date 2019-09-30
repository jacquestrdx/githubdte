
@extends('layouts.app')

@section('content')

    <!DOCTYPE html>
    <html>
    <head>
        <title>Simple Map</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
    <div id="map"></div>
    <script>
        var map;

        function initMap() {

            var myLatLng = {lat: -25.771527, lng:  28.268275 };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: myLatLng
            });

            @foreach ($locations as $location)
            var myLatLng = {lat: {{$location->lat}}, lng: {{$location->lng}} };
            @php
                $temp = "";
                foreach($location->device as $device){
                    if ($device->ping != "1"){
                        $temp = 1;
                    }
                 }
            if ($temp == 1){
            $status = "offline";
            }else{
            $status = "online";
            }
            @endphp

            var icons = {
                        offline: {
                            icon: 'offline.png'
                        },
                        online: {
                            icon: 'online.png'
                        }
                    };

            var marker{{$location->id}} = new google.maps.Marker({
                position: myLatLng,
                map: map,
                infowindow: "{{preg_replace("/\W/","",$location->name)}}",
                title: "{{$location->name}}",
                icon: icons["{{$status}}"].icon
            });


            var contentString = '<div id="content">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    '<h1 id="firstHeading" class="firstHeading"> <a target="_blank" href="{{ route('location.show',$location->id) }}">{{$location->name}}</a></h1>'+
                    '<div id="bodyContent">'+
                    '<p><b>{{$location->name}}</b>, ' +
                    @foreach ($location->device as $device)
                            @if ($device->ping != "1")
                            '<ul style="color:red;">' + "{{$device->name}}" + '</ul>' +
                            @else
                            '<ul style="color:green;">' + "{{$device->name}}" + '</ul>' +
                            @endif
                    @endforeach
                    '</div>'+
                    '</div>';
            var {{preg_replace("/\W/","",$location->name)}} = new google.maps.InfoWindow({
                content: contentString,
            });

            marker{{$location->id}}.addListener('click', function() {
                {{preg_replace("/\W/","",$location->name)}}.open(map, marker{{$location->id}});
            });

            @endforeach
        }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIrXz5_7E_iNqs2MuAxyNf0s_WDTswZ1A&callback=initMap"
            async defer></script>
    </body>
    </html>

@endsection
