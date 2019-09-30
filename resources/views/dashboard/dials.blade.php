    <div class="row">
        <div class="col-md-3">
            <div id="downdevices_div" style="width:350px; height:220px"></div>
        </div>
        <div class="col-md-3">
            <div id="power_dials_div" style="width:350px; height:220px"></div>
        </div>

        <div class="col-md-3">
            <div id="probloc_div" style="width:350px; height:220px"></div>
        </div>
        <div class="col-md-3">
            <div id="TotalPPPOEgauge_div" style="width:350px; height:220px"></div>
        </div>
    </div>

@push('scripts')
    <script>

        var maxpppoes = 0;


        function getPppoeAJAX(){


            $.ajax({
                url: '../getMaxPppoe',                  //the script to call to get data
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
                        url: '../getTotalPppoe',                  //the script to call to get data
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
                            url: '../getTotalPppoe',                  //the script to call to get data
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

        function getProblemLocationsAJAX(){

            var problocs = new JustGage({
                id: "probloc_div",
                value: 0,
                min: 0,
                max: 170,
                title: "Problem Devices"
            });

            $.ajax({
                url: '../getProblemLocations',                  //the script to call to get data
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
                    url: '../getProblemLocations',                  //the script to call to get data
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
                id: "power_dials_div",
                value: 0,
                min: 0,
                max: 10,
                title: "Down Power Monitors"
            });

            $.ajax({
                url: '../getDownPowerMons',                  //the script to call to get data
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
                    url: '../getDownPowerMons',                  //the script to call to get data
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
                url: '../getDownDevicesCount',                  //the script to call to get data
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
                    url: '../getDownDevicesCount',                  //the script to call to get data
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
                url: '../getOnlineFizzes',                  //the script to call to get data
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
                    url: '../getOnlineFizzes',                  //the script to call to get data
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

        getDownDevicesAJAX();
        getPppoeAJAX();
        getDownPowerMonsAJAX();
        getProblemLocationsAJAX();

    </script>

@endpush()
