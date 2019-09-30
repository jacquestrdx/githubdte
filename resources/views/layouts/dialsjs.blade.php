@push('scripts)
<script>


    function getPppoeAJAX(){

        var maxpppoes = 0;

        $.ajax({
            url: '{{config('url.root_url')}}/getMaxPppoe',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                maxpppoes = data;
            }
        });

        var pppoes = new JustGage({
            id: "TotalPPPOEgauge_div",
            value: 0,
            min: 0,
            max: 5000,
            levelColors:["#ff0000","#a9d70b", "#45d70b"],
            title: "Online PPPOE's"
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
</script>

<script>

    getDownDevicesAJAX();
    getPppoeAJAX();
    getDownPowerMonsAJAX();
    getProblemLocationsAJAX();

</script>
@endpush