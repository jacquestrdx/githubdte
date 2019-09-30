
{{----}}
<div class="col-md-12 col-md-offset-0" id ="home_div">
    <div class="panel panel-default">
        <div class="panel-heading">
            Dashboard  --

            <small id="Date">
            </small>
            <small id="hours"> </small>
            <small id="point">:</small>
            <small id="min"> </small>
            <small id="point">:</small>
            <small id="sec"> </small>

            <a style="float:right" href="{{config('url.root_url')}}/getWhatsappReport" target="_blank">Generate WhatsApp Report</a>
            <a style="float:right" href="{{config('url.root_url')}}/getFizWhatsappReport" target="_blank">Generate Fiz WhatsApp Report</a>
            <a style="float:right" href="{{ url('dashhistory') }}">Historical Data &nbsp</a>
        </div>

    @push('scripts')
                <script>
                    window.onload = function () {
                        document.getElementById('button').onclick = function () {
                            document.getElementById('modal').style.display = "none"
                        };
                    };
                </script>
     @endpush

        <div class="panel-body">
            <div class="row">

                <div class="col-md-6dashboard.blade.php">
                    <div class="panel panel-default" id="left-panel">
                        @include('left-panel')
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="downdevices_div" style="width:250px; height:220px"></div>
                        </div>
                        <div class="col-md-6">
                            <div id="power_div" style="width:250px; height:220px"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div id="probloc_div" style="width:250px; height:220px"></div>
                        </div>
                        <div class="col-md-6">
                            <div id="TotalPPPOEgauge_div" style="width:250px; height:220px"></div>
                        </div>
                    </div>
                </div>

    </div>

    <div id="bottom-panel">
        @include('layouts.bottom-panel')
    </div>


        <a style="float: right;" href="{{ url('/devicesdown') }}">Show down devices</a>
        <!--                     <div class="panel-body">
                              <table class="table hover">

                              </table> -->


    </div>

        @push('scripts')
        <script>

            var maxpppoes = 0;


            function getPppoeAJAX(){


                $.ajax({
                    url: '{{config('url.root_url')}}/getMaxPppoe',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        maxpppoes = data;
                        var pppoes = new JustGage({
                            id: "TotalPPPOEgauge_div",
                            value: 0,
                            min: 0,
                            max: maxpppoes,
                            levelColors:["#ff0000","#a9d70b", "#45d70b"],
                            title: "Online Radius users"
                        });

                        $.ajax({
                            url: '{{config('url.root_url')}}/getTotalPppoe',                  //the script to call to get data
                            data: "",                        //you can insert url arguments here to pass to api.php
                                                             //for example "id=5&parent=6"
                            dataType: 'json',                //data format
                            success: function(data)          //on receive of reply
                            {
                                pppoes.refresh(data)
                            }
                        });

                        setInterval(function() {
                            $.ajax({
                                url: '{{config('url.root_url')}}/getTotalPppoe',                  //the script to call to get data
                                data: "",                        //you can insert url arguments here to pass to api.php
                                                                 //for example "id=5&parent=6"
                                dataType: 'json',                //data format
                                success: function(data)          //on receive of reply
                                {
                                    pppoes.refresh(data);
                                }
                            });
                        }, 30000);
                    }
                });






            }

            setInterval(function(){
                $('#left-panel').load('{{config('url.root_url')}}/getDashboardOutages');
                $('#notificationbar').load('{{config('url.root_url')}}/getnotificationbar');
                getSounds();
            }, 30000) /* time in milliseconds (ie 2 seconds)*/

            setInterval(function(){
                $('#bottom-panel').load('{{config('url.root_url')}}/getdownbgp');
            }, 30000) /* time in milliseconds (ie 2 seconds)*/


            function getProblemLocationsAJAX(){

                var problocs = new JustGage({
                    id: "probloc_div",
                    value: 0,
                    min: 0,
                    max: 170,
                    title: "Problem Devices"
                });

                $.ajax({
                    url: '{{config('url.root_url')}}/getProblemLocations',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        problocs.refresh(data);
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getProblemLocations',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            problocs.refresh(data);
                        },
                        error: function () {
//                    alert('Session is expired. Login again');
                            window.location.href = '/home';
                        }
                    });

                }, 30000);
            }

            function getDownPowerMonsAJAX(){

                var downpower = new JustGage({
                    id: "power_div",
                    value: 0,
                    min: 0,
                    max: 10,
                    title: "Down Power Monitors"
                });

                $.ajax({
                    url: '{{config('url.root_url')}}/getDownPowerMons',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        downpower.refresh(data);
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getDownPowerMons',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            downpower.refresh(data);
                        }
                    });

                }, 30000);
            }

            function getDownDevicesAJAX(){

                var downdevices = new JustGage({
                    id: "downdevices_div",
                    value: 0,
                    min: 0,
                    max: 10,
                    title: "Down Devices"
                });

                $.ajax({
                    url: '{{config('url.root_url')}}/getDownDevicesCount',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        downdevices.refresh(data);
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getDownDevicesCount',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            downdevices.refresh(data);
                        }
                    });

                }, 30000);

            }

            //
        </script>

        <script type="text/javascript">
            function play_sound() {
                var audioElement = document.createElement('audio');
                audioElement.setAttribute('src', 'down2.mp3');
                audioElement.setAttribute('autoplay', 'autoplay');
                audioElement.load();
                audioElement.play();
                audioElement.play();
            }


            function getOnlineFizzes(){

                var onlinefizzes = new JustGage({
                    id: "getOnlineFizzes_div",
                    value: 0,
                    min: 0,
                    max: 1050,
                    levelColors:["#ff0000","#a9d70b", "#45d70b"],
                    title: "Online Fizzes"
                });

                $.ajax({
                    url: '{{config('url.root_url')}}/getOnlineFizzes',                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function(data)          //on receive of reply
                    {
                        onlinefizzes.refresh(data)
                    }
                });

                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/getOnlineFizzes',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            onlinefizzes.refresh(data);
                        }
                    });
                }, 30000);

            }
        </script>

        <script>

            getDownDevicesAJAX();
            getPppoeAJAX();
            getDownPowerMonsAJAX();
            getProblemLocationsAJAX();

        </script>




        @endpush

</div>

</div>

