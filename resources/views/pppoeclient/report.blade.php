@extends('layouts.app')

@section('title', 'pppoe')

@section('content')

    <div class="row">
        <div class="col-md-12 col-md-offset-0">

            <div class="panel panel-default">

                <div class="panel-heading">All Pppoe Sessions

                </div>

                <div class="panel-body">
                    </br>
                    <div id="links" style="position: relative">
                        <a href="{{ url('/pppoeclient') }}"  class="btn btn-default">Show All</a>
                        <a href="{{ url('/pppoeclient/offline') }}"  class="btn btn-default">Only offline</a>
                        <a href="{{ url('/pppoeclient/thismonth') }}"  class="btn btn-default">Offline this month</a>
                        <a href="{{ url('/pppoeclient/report') }}"  class="btn btn-default">Report</a>

                    </div>
                    </br>

                    <div>
                        <div class="col-md-3"><div id="downpppoealltime_div"></div></div>
                        <div class="col-md-3"><div id="downpppoethismonth_div"></div></div>
                        <div class="col-md-3"><div id="downpppoenoreason_div"></div></div>






                    </div>


                    @push('scripts')
                    <script>

                        function getDownPPPoeAllTime(){

                            var downpppoeforever = new JustGage({
                                id: "downpppoealltime_div",
                                value: 0,
                                min: 0,
                                max: 1000,
                                title: "Down Pppoe (All time)"
                            });

                            $.ajax({
                                url: '{{config('url.root_url')}}/getDownPPPoeAllTime',                  //the script to call to get data
                                data: "",                        //you can insert url arguments here to pass to api.php
                                                                 //for example "id=5&parent=6"
                                dataType: 'json',                //data format
                                success: function(data)          //on receive of reply
                                {
                                    downpppoeforever.refresh(data);
                                }
                            });

                            setInterval(function() {
                                $.ajax({
                                    url: '{{config('url.root_url')}}/getDownPPPoeAllTime',                  //the script to call to get data
                                    data: "",                        //you can insert url arguments here to pass to api.php
                                                                     //for example "id=5&parent=6"
                                    dataType: 'json',                //data format
                                    success: function(data)          //on receive of reply
                                    {
                                        downpppoeforever.refresh(data);
                                    }
                                });

                            }, 30000);

                        }

                        function getDownPPPoeNoReason(){

                            var downpppoenoreason = new JustGage({
                                id: "downpppoenoreason_div",
                                value: 0,
                                min: 0,
                                max: 1000,
                                title: "Down Pppoe (No Reason)"
                            });

                            $.ajax({
                                url: '{{config('url.root_url')}}/getDownPPPoeNoReason',                  //the script to call to get data
                                data: "",                        //you can insert url arguments here to pass to api.php
                                                                 //for example "id=5&parent=6"
                                dataType: 'json',                //data format
                                success: function(data)          //on receive of reply
                                {
                                    downpppoenoreason.refresh(data);
                                }
                            });

                            setInterval(function() {
                                $.ajax({
                                    url: '{{config('url.root_url')}}/getDownPPPoeNoReason',                  //the script to call to get data
                                    data: "",                        //you can insert url arguments here to pass to api.php
                                                                     //for example "id=5&parent=6"
                                    dataType: 'json',                //data format
                                    success: function(data)          //on receive of reply
                                    {
                                        downpppoenoreason.refresh(data);
                                    }
                                });

                            }, 30000);

                        }

                        function getDownPPPoeThisMonth(){

                            var downpppoethismonth = new JustGage({
                                id: "downpppoethismonth_div",
                                value: 0,
                                min: 0,
                                max: 1000,
                                title: "Down Pppoe (This Month)"
                            });

                            $.ajax({
                                url: '{{config('url.root_url')}}/getDownPPPoeThisMonth',                  //the script to call to get data
                                data: "",                        //you can insert url arguments here to pass to api.php
                                                                 //for example "id=5&parent=6"
                                dataType: 'json',                //data format
                                success: function(data)          //on receive of reply
                                {
                                    downpppoethismonth.refresh(data);
                                }
                            });

                            setInterval(function() {
                                $.ajax({
                                    url: '{{config('url.root_url')}}/getDownPPPoeThisMonth',                  //the script to call to get data
                                    data: "",                        //you can insert url arguments here to pass to api.php
                                                                     //for example "id=5&parent=6"
                                    dataType: 'json',                //data format
                                    success: function(data)          //on receive of reply
                                    {
                                        downpppoethismonth.refresh(data);
                                    }
                                });

                            }, 30000);

                        }

                        getDownPPPoeThisMonth();
                        getDownPPPoeAllTime();
                        getDownPPPoeNoReason();

                    </script>

                    @endpush

            </div>
        </div>
    </div>
@endsection
