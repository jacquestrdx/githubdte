
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
<div class="col-md-11">
<div class="panel panel-default">
<div class="panel-heading" style="font-style:bold">{{$location->name}}</div>
<div class="panel-body">
             <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script><div style='overflow:hidden;height:440px;width:1000px;'><div id='gmap_canvas' style='height:700px;width:1000px;'></div><div><small><a href="http://embedgooglemaps.com">                                                            </a></small></div><div><small><a href="http://www.autohuren.world/"> </a></small></div><style>#gmap_canvas img{max-width:;background:none!important}</style></div>

             <script type='text/javascript'>
             function init_map(){

                    var myOptions = {zoom:16,center:new google.maps.LatLng({{$location->lat}},{{$location->lng}}),mapTypeId: google.maps.MapTypeId.HYBRID};

                    map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng({{$location->lat}},{{$location->lng}})});

                    infowindow = new google.maps.InfoWindow({content:'<strong>{{$location->name}}</strong>'});google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});infowindow.open(map,marker);

                }google.maps.event.addDomListener(window, 'load', init_map);google.maps.event.addDomListener(window, "resize", function() {
                        var center = map.getCenter();
                        google.maps.event.trigger(map, "resize");
                         map.setCenter(center); 
                    });
                </script>
</div>
</div>
</div>
</div>
        </div>
    </div>
@endsection
