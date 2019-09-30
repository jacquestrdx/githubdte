@extends('layouts.app')

@section('title', 'SLA Report')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All devices</div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                       id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                        <tr>
                            <th>
                                Device name
                            </th>
                            <th>
                                Average Uptime
                            </th>
                            <th>
                                Total Downtime
                            </th>
                            <th>
                                Show details
                            </th>
                            <th>
                            </th>
                        </tr>
                            @foreach ($slareport as $key => $table)
                                        <div>

                                            @php
                                                $nospacekey = str_replace(' ','',$key);
                                            @endphp

                                            <!-- Trigger/Open The Modal -->

                                                <tr>
                                                    <td>
                                                        {!! $key !!}
                                                    </td>
                                                    <td>
                                                        {{$totals[$key]['0']['0']}}
                                                    </td>
                                                    <td>
                                                        {{$totals[$key]['0']['1']}}
                                                    </td>
                                                    <td>
                                                        <button id="myBtn{!!$nospacekey!!}">Open {{$key}}</button>
                                                    </td>
                                            <td>
                                            <!-- The Modal -->
                                            <div id="myModal{!!$nospacekey!!}" class="modal">
                                                <!-- Modal content -->
                                                <div class="modal-content">
                                                    <span class="close" id="close{!!$nospacekey!!}">X</span>
                                                    <table class="table table-striped table-bordered table-hover no-footer">
                                                        <thead>
                                                        <tr>
                                                            <th>Device Name</th>
                                                            <th>Down Minutes</th>
                                                            <th>ICMP Availability</th>
                                                        </tr>
                                                        </thead>
                                                        @foreach ($table as $row)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ route('device.show', $device->getDeviceIDFromName($row['0'])) }}">  {{$row['0']}}</a>

                                                                </td>
                                                                <td>
                                                                    {{\App\Http\Controllers\SlaReportController::secondsToTime($row['1']*60)}}
                                                                </td>
                                                                <td>
                                                                    {{$row['2']}} %

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            </td>

                                            <html>
                                                <body>
                                                <script>
                                                    // Get the modal
                                                    var modal{!!$nospacekey!!} = document.getElementById('myModal{!!$nospacekey!!}');

                                                    // Get the button that opens the modal
                                                    var btn{!!$nospacekey!!} = document.getElementById("myBtn{!!$nospacekey!!}");

                                                    // Get the <span> element that closes the modal
                                                    var span = document.getElementById("close{!!$nospacekey!!}");

                                                    // When the user clicks on the button, open the modal
                                                    btn{!!$nospacekey!!}.onclick = function() {
                                                        modal{!!$nospacekey!!}.style.display = "block";
                                                    }

                                                    // When the user clicks on <span> (x), close the modal
                                                    span.onclick = function() {
                                                        modal{!!$nospacekey!!}.style.display = "none";
                                                    }

                                                    // When the user clicks anywhere outside of the modal, close it
                                                    window.onclick = function(event) {
                                                        if (event.target == modal) {
                                                            modal{!!$nospacekey!!}.style.display = "none";
                                                        }
                                                    }
                                                </script>
                                                </body>
                                            </html>

                                        </div>

                                @endforeach
                        </table>
                    </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
