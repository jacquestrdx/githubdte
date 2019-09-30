<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>

    <title>@yield('title', 'DTE')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link rel="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/vendor/hover.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.20.1/vis.min.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.20.1/vis.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <!-- Styles -->
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('vendor/metisMenu/metisMenu.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('vendor/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('vendor/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

    <!-- Custom Fonts -->
    <link href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('vendor/modal/modal.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all"/>

    <style>
        textarea {
            cols: 30;
        }
        .dropdown-submenu {
            position: relative;
        }
        a.fa-globe {
            position: relative;
            font-size: 2em;
            color: grey;
            cursor: pointer;
        }
        span.fa-comment {
            position: absolute;
            font-size: 0.6em;
            top: -4px;
            color: red;
            right: -4px;
        }
        span.num {
            position: absolute;
            font-size: 0.3em;
            top: 1px;
            color: #fff;
            right: 2px;
        }
        .container { width: auto; }

        .circlered{ background: red; border-radius: 64px; border: 2px solid red;margin-bottom: 20px }
        .circlegreen{ background: green; border-radius: 64px; border: 2px solid green;margin-bottom: 20px }
        .circle div { position:relative;  color: white; text-align:center;margin-bottom: 20px }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        table.dataTable tbody tr { font-size: 11px; }
        table.dataTable tbody th { font-size: 11px; }
        table.dataTable tbody td { font-size: 11px; }

        body {
            font-family: 'Lato';
        }


        #div_down_links {

        }
        .td {
            word-break: break-all;
        }

        #load{
            width:100%;
            height:100%;
            position:fixed;
            z-index:9999;
            background:url("https://www.creditmutuel.fr/cmne/fr/banques/webservices/nswr/images/loading.gif") no-repeat center center rgba(0,0,0,0.25)
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        /* The Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 600px){
            .dataTable_wrapper {
                max-width: 30%;
            }
        }

        .fa-btn {
            margin-right: 6px;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
        }

        /* Tooltip text */
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            /* Position the tooltip text - see examples below! */
            position: absolute;
            z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
        @stack('styles')


    </style>
    @stack('head')

</head>

<body id="app-layout">


@include('layouts.menu')
@include('layouts.flashpopup')

{{--@if ($view_name =="auth.register")--}}
{{--{{dd("Unautherized")}}--}}
{{--@endif--}}
@if ($view_name =="auth.login" or $view_name =="auth.register")
    @yield('content')
    @yield('js')
@elseif ( (Auth::guest()))
    {{dd("Unautherized")}}
@elseif ( (Auth::user()->user_type=="CC") and (strpos($view_name, "edit")) )
    {{dd("Unautherized")}}
@elseif ( (Auth::user()->verified==0) )
    {{dd("Get your SYSADMIN to VALIDATE your account")}}
@else
    @if (\Auth::user()->user_type=="view_only")
        @if ( ($view_name =="home") or ($view_name =="layouts.fizdashboard") or ($view_name =="isizwe.report") or ($view_name =="location.index") or ($view_name =="location.show") or ($view_name =="device.devicemap") )
        @else
            {{dd("Unauthorized")}}
        @endif
    @endif
    @yield('content')
    @yield('js')
@endif





@stack('body')

<!-- JavaScripts -->

<!-- DataTables JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
{{--<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>--}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('vendor/metisMenu/metisMenu.min.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
<script src="{{ asset('vendor/modal/raphael-2.1.4.min.js') }}"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="{{ asset('vendor/modal/justgage.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/r-2.2.2/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/r-2.2.2/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

<!-- DataTables JavaScript -->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://d3js.org/d3.v4.min.js"></script>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>--}}

<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/gauge.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
<script src="{{ asset('workflowChart.js') }}"></script>

{{--<script>--}}
{{--function reloadHome () {--}}
{{--$('#home_div').load('/dashboard #home_div');--}}
{{--}--}}
<script>

    <!--Custom multi level dropdown-->
    @if(Auth::guest())
    @else
    function PlaySound(){
        var obj = document.createElement("audio");
        obj.src="/down2.mp3";
        obj.volume=0.10;
        obj.autoPlay=false;
        obj.preLoad=true;
        obj.play();
    }

    function getSounds(){
        $.ajax({
            url: '{{config('url.root_url')}}/getNotificationSounds/{!! Auth::user()->id !!}',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                sounds = data;
                $.each(sounds, function (index,value) {
                    // PlaySound();
                });
            }
        });

    }

    function Notify(value){
        var re = new RegExp("down");
        if (re.test(value['1'])) {
            $(".navbar-right").notify(value['1']);
        } else {
            $(".navbar-right").notify(value['1'],"success");
        }
    }

    setInterval(function(){
        $('#navbar-not').load('{{config('url.root_url')}}/getnotificationbar');
    }, 15000) /* time in milliseconds (ie 2 seconds)*/

    setInterval(function(){
        getSounds();
    }, 15000); /* time in milliseconds (ie 2 seconds)*/

</script>


@if ($view_name =="layouts.fizdashboard")
    <script>
        function getOnlineFizzes(){

            var onlinefizzes = new JustGage({
                id: "getOnlineFizzes_div",
                value: 0,
                min: 0,
                max: 5000,
                levelColors:["#ff0000","#a9d70b", "#45d70b"],
                title: "Online PPPOE's"
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

@endif




<!-- Custom Theme JavaScript -->


@if($view_name=="device.stations")
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "/showallstationsajax",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#stations-all').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        columns: [
                            { title: "Id" },
                            { title: "Name" },
                            { title: "Ip" },
                            { title: "CCQ" },
                            { title: "Airmax Quality" },
                            { title: "Airmax Capacity" },
                            { title: "Signal" },
                            { title: "Tx Power" },
                            { title: "SSID" }
                        ]
                    } );
                }
            });
        } );
    </script>
@endif


@if($view_name=="job.index")
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "/jobs/all/ajax",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#jobs-all').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        columns: [
                            { title: "id"},
                            { title: "date"},
                            { title: "location_id"},
                            { title: "technician"},
                            { title: "time_spent"},
                            { title: "km"},
                            { title: "job_description"},
                            { title: "resolution"},
                            { title: "fiz_live"},
                            { title: "signal"},
                            { title: "pi_down"},
                            { title: "pi_up"},
                            { title: "mweb_down"},
                            { title: "mweb_up"},
                            { title: "Edit"},
                        ]
                    } );
                }
            });
        } );
    </script>
@endif


@if($view_name=="location.index")
@endif

@if($view_name=="isizwe.reportweekly")
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "/isizwe/device/weekly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#devicetable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Time Down" },
                            { title: "Uptime" },
                        ]
                    } );
                }
            });

            $.ajax({
                url: "/isizwe/fiz/weekly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#fiztable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Description" },
                            { title: "Time Down" },
                            { title: "Uptime" },
                        ]
                    } );
                }
            });

            $.ajax({
                url: "/isizwe/latency/weekly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#latencytable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Description" },
                            { title: "Latency" },
                        ]
                    } );
                }
            });

        } );
    </script>
@endif


@if($view_name=="isizwe.reportmonthly")
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "/isizwe/device/monthly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#devicetable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Time Down" },
                            { title: "Uptime" },
                        ]
                    } );
                }
            });

            $.ajax({
                url: "/isizwe/fiz/monthly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#fiztable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Description" },
                            { title: "Time Down" },
                            { title: "Uptime" },
                        ]
                    } );
                }
            });

            $.ajax({
                url: "/isizwe/latency/monthly",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#latencytable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        "lengthMenu": [[30, 50, 100, -1], [30, 50, 100, "All"]],
                        columns: [
                            { title: "Name" },
                            { title: "Description" },
                            { title: "Latency" },
                        ]
                    } );
                }
            });

        } );
    </script>
@endif

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.js"></script>

<script>

    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true,
            "pageLength": 100,

        });
    });

    $(document).ready(function () {
        $('#dataTables-example2').DataTable({
            responsive: true,
            "pageLength": 100,
        });
    });



    $(document).ready(function(){
        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
        $('#navbar-not').load('{{config('url.root_url')}}/getnotificationbar');
    });

    $(function() {
        $('.confirm').click(function() {
            return window.confirm("Are you sure?" +
                "\n THIS IS PERMANANET AND CANNOT BE REVERSED");


        });
    });

    $(function() {
        $('.confirm_location').click(function() {
            return window.confirm("Are you sure?" +
                "\n THIS IS PERMANANET AND CANNOT BE REVERSED!! ALL DEVICES AND HISTORICAL DATA WILL BE PURGED");

        });
    });

    $(function() {
        $('.confirm-test').click(function() {
            return window.confirm("Are you sure?" +
                "\n This will completely max out the sector?");
        });
    });



</script>
<!-- Dashboard Javascript -->






<!-- Initialise Page-Level DataTables -->

@if ($view_name =="device.show")
    @if ($device->devicetype_id == "20")
        <script>
            $(document).ready(function () {
                $('#queue_table').load('{{config('url.root_url')}}/smtp/queue/{{$device->id}}');
            });
            setInterval(function(){
                $('#queue_table').load('{{config('url.root_url')}}/smtp/queue/{{$device->id}}');
            }, 15000) /* time in milliseconds (ie 2 seconds)*/
        </script>
    @endif
@endif


@endif
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
@stack('scripts')

</body>
</html>
