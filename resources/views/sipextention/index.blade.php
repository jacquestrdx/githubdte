


@extends('layouts.app')

@section('content')
    <div class="container">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4 id="totalactive">Total active: </h4>
                        @foreach($queues as $queue)
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                <h4>{{$queue->name}}</h4>
                                @foreach ($queue->sipextentions as $sipextention)
                                    <div class="col-md-3" id="{!!$sipextention->ext!!}">
                                       {{$sipextention->ext}} NOT REGISTERED
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

            function getActiveCalls(){
                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/active/calls',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            refreshActive(data);
                        }
                    });

                }, 1000);
            }
            function getSipExtentions(){
                setInterval(function() {
                    $.ajax({
                        url: '{{config('url.root_url')}}/sipextensions',                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                        dataType: 'json',                //data format
                        success: function(data)          //on receive of reply
                        {
                            refreshExtensions(data);
                        }
                    });

                }, 1000);
            }

            $( document ).ready(function() {
                getSipExtentions();
                getActiveCalls();
            });
            function refreshActive(item){
                document.getElementById('totalactive').innerHTML = 'Total active: '+ item;
            }

            function refreshExtensions(item){
                for (var i = 0, len = item.length; i < len; i++) {
                    console.log(item[i]['ext'] +' - '+item[i]['name']+' - '+item[i]['status']);
                    document.getElementById(item[i]['ext']).innerHTML = item[i]['ext'] +' - '+item[i]['name']+' - '+item[i]['status'];
                }
            }


        </script>
    @endpush

@endsection
