@extends('layouts.app')

@section('title', 'Hotspot Dashboard')

@section('content')

        <div class="container col-md-12">
            <div class="col-md-4">
                <div id="getOnlineHotspot_div" style="width:400px; height:320px">
                </div>
            </div>
            <div class="col-md-4">
                <div id="getOnlineHotspotRouters_div" style="width:400px; height:320px">
                </div>
            </div>
        </div>


@push('scripts')
<script>

    getOnlineHotspot();
    getOnlineHotspotRouters();

    function getOnlineHotspot(){

        $.ajax({
            url: '{{config('url.root_url')}}/getMaxHotspotUsers',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                maxusers = data;
                var onlinehotspotusers = new JustGage({
                    id: "getOnlineHotspot_div",
                    value: 0,
                    min: 0,
                    max: maxusers,
                    levelColors:["#ff0000","#a9d70b", "#45d70b"],
                    title: "Online Hotspot Users"
                });
                $.ajax({
                    url: '{{config('url.root_url')}}/getOnlineHotspotUsers',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        onlinehotspotusers.refresh(data)
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getOnlineHotspotUsers',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            onlinehotspotusers.refresh(data);
                        }
                    });
                }, 30000);
            }
        });




    }

    function getOnlineHotspotRouters(){
        $.ajax({
            url: '{{config('url.root_url')}}/getMaxHotspotRouters',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                maxrouters = data;
                var onlinehotspotrouters = new JustGage({
                    id: "getOnlineHotspotRouters_div",
                    value: 0,
                    min: 0,
                    max: maxrouters,
                    levelColors:["#ff0000","#a9d70b", "#45d70b"],
                    title: "Online Hotspot Routers"
                });

                $.ajax({
                    url: '{{config('url.root_url')}}/getOnlineHotspotRouters',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        onlinehotspotrouters.refresh(data)
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getOnlineHotspotRouters',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            onlinehotspotrouters.refresh(data);
                        }
                    });
                }, 30000);

            }
        });






    }

    function getMaxHotspotRouters(){
        $.ajax({
            url: '{{config('url.root_url')}}/getMaxHotspotRouters',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                return data;
            }
        });
    }

    function getMaxHotspotUsers(){
        $.ajax({
            url: '{{config('url.root_url')}}/getMaxHotspotUsers',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                return data;
            }
        });
    }

</script>


@endpush




@endsection