@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading" style="height: 55px">Dashboard --
                        <small id="Date"></small>
                        <small id="hours"> </small>
                        <small id="point">:</small>
                        <small id="min"> </small>
                        <small id="point">:</small>
                        <small id="sec"> </small>
                        <small>&nbsp; &nbsp;</small>
                        <small id="sysload"></small>
                        <small id="syspol"></small>
                    <div style="float:right">
                        <a  href="/getWhatsappReport" target="_blank" class="btn btn-default">Generate WhatsApp Report</a>
                        <a  href="/getFizWhatsappReport" target="_blank" class="btn btn-default">Generate FIZ WhatsApp Report</a>
                        <a  href="/dashhistory" target="_blank" class="btn btn-default">Show Dashboard graphs</a>
                    </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @include('dashboard.dials')
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <b>
                                            Outages
                                        </b>
                                    </div>
                                    </br>

                                    <div id="outages_div">
                                        @include('dashboard.outages')
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <b>
                                                Power Status
                                            </b>
                                    </div>
                                        </br>
                                        <div id="power_div">
                                            @include('dashboard.power')
                                        </div>

                                    </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <b>
                                            Backhaul Status
                                        </b>
                                    </div>
                                    </br>

                                    <div id="backhaul_div">
                                        @include('dashboard.backhauls')
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>





                    {{--</div>--}}
    @push('scripts')
        @foreach($sounds as $sound)
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
            <script type="text/javascript">play_sound();</script>
        @endforeach

        <script>

            {{--SOUND FUNCTION--}}

            function p() {
                var audioElement = document.createElement('audio');
                audioElement.setAttribute('src', 'down2.mp3');
                audioElement.setAttribute('autoplay', 'autoplay');
                audioElement.load();
                audioElement.play();
                audioElement.play();
            }

            {{--LIVE CLOCK--}}

            $(document).ready(function() {
                var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
                var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

                var newDate = new Date();
                newDate.setDate(newDate.getDate());
                $('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

                setInterval( function() {
                    // Create a newDate() object and extract the seconds of the current time on the visitor's
                    var seconds = new Date().getSeconds();
                    // Add a leading zero to seconds value
                    $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
                },1000);

                setInterval( function() {
                    // Create a newDate() object and extract the minutes of the current time on the visitor's
                    var minutes = new Date().getMinutes();
                    // Add a leading zero to the minutes value
                    $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
                },1000);

                setInterval( function() {
                    // Create a newDate() object and extract the minutes of the current time on the visitor's

                    $.ajax({
                        url: '/system/load',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function (data) {
                            $("#sysload").html(' -- System Load: '+ data);
                        }
                    });
                    // Add a leading zero to the minutes value
                },10000);

                setInterval( function() {
                    // Create a newDate() object and extract the minutes of the current time on the visitor's

                    $.ajax({
                        url: '/system/polling',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function (data) {
                            $("#syspol").html(' -- Currently polling: '+ data + ' devices --');
                        }
                    });
                    // Add a leading zero to the minutes value
                },10000);






                setInterval( function() {
                    // Create a newDate() object and extract the hours of the current time on the visitor's
                    var hours = new Date().getHours();
                    // Add a leading zero to the hours value
                    $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
                }, 1000);

            });
        </script>

        <script>
            setInterval( function() {
                $('#outages_div').load('/dashboard/outages');
                $('#power_div').load('/dashboard/power');
                $('#backhaul_div').load('/dashboard/backhauls');
            },30000);

        </script>
    @endpush

@endsection
