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

                        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="pppoe-datatable" role="grid" aria-describedby="dataTables-example_info">
                            </table>
                        </div>
                    </div>
                    @push('scripts')
                        <script>
                            $(document).ready(function() {

                                $.ajax({
                                    url: "/pppoe/all/ajax",                  //the script to call to get data
                                    data: "",                        //you can insert url arguments here to pass to api.php
                                                                     //for example "id=5&parent=6"
                                    dataType: 'json',                //data format
                                    success: function (dataSet)          //on receive of reply
                                    {
                                        $('#pppoe-datatable').DataTable( {
                                            colReorder: true,
                                            dom: 'Blfrtip',
                                            buttons: [
                                                'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                                            ],
                                            data: dataSet,
                                            columns: [
                                                { title: "Id" },
                                                { title: "Username" },
                                                { title: "IP" },
                                                { title: "Mac" },
                                                { title: "Vendor" },
                                                { title: "Concentrator" },
                                                { title: "SSID" },
                                                { title: "Online" },
                                                { title: "Last Seen" },
                                                { title: "Reason" }

                                            ]
                                        } );
                                    }
                                });
                            } );
                        </script>
                    @endpush
                </div>
            </div>
        </div>
@endsection
