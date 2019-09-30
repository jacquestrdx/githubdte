@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <strong>
                            {{$location->name}}
                        </strong>
                        <button style="float:right">
                            <a  href="{{ route('location.edit',$location->id) }}">
                                Edit<span class="fa fa-edit "></span>
                            </a>
                        </button>
                        <button style="float:right">
                            <a  href="{{ url("/locations/scan/".$location->id) }}">
                                Scan Subnet<span class="fa fa-search "></span>
                            </a>
                        </button>
                        <button style="float:right">
                            <a style="float:right" href="/pollhighsite/{!! $location->id !!}">
                                Refresh<span class="fa fa-refresh"></span>
                            </a>
                        </button>
                        <button style="float:right">
                            <a style="float:right" href="/devices/create/{!! $location->id !!}">
                                Add a device<span class="fa fa-plus"></span>
                            </a>
                        </button>
                        @if (\Auth::user()->user_type=="admin")
                            <button style="float:right">
                                <a class="confirm_location" style="color:darkred;float:right" href="/location/destroy/{{$location->id}}">Delete
                                </a>
                            </button>
                        @endif
                    </div>

                    <div class="panel-heading">Location Summary</div>
                    <div class="panel-body">



                        <table class="table hover">
                            <tr>
                                <th>Description</th>
                                <td>{{$location->description}}</td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <td>{{$location->lat}}</td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>

                                </td>
                            </tr>

                            <tr>
                                <th>Longitude</th>
                                <td>{{$location->lng}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>



                        </table>
                        <div class="panel panel-default">

                            <div class="panel-heading">Reporting</div>

                            <ul>
                                <li>
                                    <a href="{{ route('location.frequencyreport',$location->id) }}">Report on frequencies </a>
                                </li>
                                <li>
                                    <a href="{{ route('location.sectors',$location->id) }}">Report on sectors </a>
                                </li>
                                <li>
                                    <a href="/locations/backhauls/{!! $location->id !!}">Report on Backhauls</a>
                                </li>
                            </ul>

                        </div>


                        <div class="row">

                            <div class="col-md-12 col-md-offset-0">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><strong>Owner Contact Details</strong></div>
                                    <div class="panel-body">
                                        <table class="table hover">
                                            <tr>
                                                <th style="width:50%;">Highsite Contact</th>
                                                <td>
                                                    <a href="/hscontact/{{$location->hscontact->id or ""}}">{{ $location->hscontact->name}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%;">Contact Cell number</th>
                                                <td>{{ $location->hscontact->cellnum or "" }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @php
                            foreach ($location->device as $device){
                                if ( ($device->devicetype_id=="2") or ($device->devicetype_id=="22")  or ($device->devicetype_id=="17") ){
                                    $SHOW_TABLES = 1;
                                }
                            }
                        if (!isset($SHOW_TABLES)){
                            $SHOW_TABLES = 0;
                        }

                        @endphp


                        <div class="panel panel-default">
                            <div class="panel-heading">All Devices at this location</div>
                            <div class="panel-body">

                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                           id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                                        <thead>
                                        <th>Device Name</th>
                                        <th>Device IP</th>
                                        <th>Ping Status</th>
                                        <th>Device Type</th>
                                        <th>Stations</th>
                                        <th>Model</th>
                                        <th>Uptime</th>
                                        <th>Speed Sold</th>
                                        <th>Edit</th>
                                        <th>Last Down</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($location->device as $device)
                                            <tr>
                                                <td>{{$device->name}}</td>
                                                <td><a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a></td>

                                                @if($device->ping==1)
                                                    <td style="color:green">Up</td>
                                                @else
                                                    <td style="color:red">Down</td>
                                                @endif
                                                <td>{{$device->devicetype->name}}</td>

                                                @if (($device->devicetype_id=="2"))
                                                    <td>{{$device->active_stations}}</td>
                                                @endif
                                                @if ($device->devicetype_id =="1")
                                                    <td>{{$device->active_pppoe}}</td>
                                                @endif
                                                @if (($device->devicetype_id !="1") AND ($device->devicetype_id !="2"))
                                                    <td>{{$device->active_stations}}</td>
                                                @endif
                                                <td>
                                                    {{$device->model}}
                                                </td>
                                                <td>
                                                    @if ($device->uptime > 86400)
                                                        <p>{!! gmdate("d \d H:i:s",time()) !!}</p>
                                                    @else
                                                        <p>{!! gmdate("H:i:s",time()) !!}</p>
                                                    @endif
                                                </td>
                                                @if ( ($device->devicetype_id =="2") OR ($device->devicetype_id =="22") OR ($device->devicetype_id =="17") OR ($device->devicetype_id =="5"))
                                                    <td>{{$device->comment}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>

                                                    <a href="{{ route('device.edit',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                                    </a>
                                                </td>
                                                <td>{{$device->lastdown}}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="row col-md-12">

                            <div class="panel panel-default">
                                <div class="panel-heading"><strong>Job Cards</strong></div>
                                <div class="panel-body">
                                    <table class="table hover">
                                        <tr>
                                            <th>Job ID</th>
                                            <th>Date</th>
                                            <th>Technician</th>
                                            <th>Resolution</th>
                                        </tr>
                                        {{--@if (isset($location->highsiteforms))--}}
                                        @foreach ($location->jobs as $job)
                                            <tr>
                                                <td><a href="/job/{{$job->id}}">{{$job->id}}</a></td>
                                                <td>{{$job->date}}</td>
                                                <td>{{$job->technician}}</td>
                                                <td>{{$job->resolution}}</td>
                                            </tr>
                                        @endforeach
                                        {{--@endif--}}
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if ($SHOW_TABLES ==1)

                            <div class="dataTable_wrapper" id="devices-datatable-div">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                       id="statables-datatable" role="grid" aria-describedby="dataTables-example_info">
                                </table>
                            </div>

                            @push('scripts')
                                <script>
                                    $(document).ready(function() {

                                        $.ajax({
                                            url: "/statable/location/{!! $location->id !!}",                  //the script to call to get data
                                            data: "",                        //you can insert url arguments here to pass to api.php
                                                                             //for example "id=5&parent=6"
                                            dataType: 'json',                //data format
                                            success: function (dataSet)          //on receive of reply
                                            {
                                                $('#statables-datatable').DataTable( {
                                                    colReorder: true,
                                                    dom: 'Blfrtip',
                                                    buttons: [
                                                        'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                                                    ],
                                                    data: dataSet,
                                                    columns: [
                                                        { title: "Name"},
                                                        { title: "Mac Address"},
                                                        { title: "Last IP"},
                                                        { title: "Latency"},
                                                        { title: "Signal"},
                                                        { title: "Distance"},
                                                        { title: "Rates"},
                                                        { title: "SSID"},
                                                        { title: "Time Connected"},
                                                        { title: "Connected"},
                                                    ]
                                                } );
                                            }
                                        });
                                    } );
                                </script>
                            @endpush

                        @endif

                    </div>
                </div>

@endsection
