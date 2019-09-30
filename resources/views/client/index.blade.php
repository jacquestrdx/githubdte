@extends('layouts.app')

@section('title', 'All Clients')

@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="{{ url('/clients/vip/all') }}"  class="btn btn-default">Show All</a>
                    <a href="{{ url('/clients/vip/online') }}"  class="btn btn-default">Show Online</a>
                    <a href="{{ url('/clients/vip/offline') }}"  class="btn btn-default">Only offline</a>
                    <a href="{{ url('/client') }}"  class="btn btn-default">List all</a>
                    <a href="{{ route('client.create') }}">
                        <span class="fa fa-plus-square"></span> Add
                    </a>
                    <div class="row"></div>

                    <div class="dataTable_wrapper" id="clients-datatable-div">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                               id="clients-datatable" role="grid" aria-describedby="dataTables-example_info">
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
        <script>
            $(document).ready(function() {

                $.ajax({
                    url: "/client/ajax/all",                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function (dataSet)          //on receive of reply
                    {
                        $('#clients-datatable').DataTable( {
                            colReorder: true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                            ],
                            data: dataSet,
                            columns: [
                                { title: "ID"},
                                { title: "Username"},
                                { title: "Name"},
                                { title: "IP"},
                                { title: "Reseller"},
                                { title: "Cap Used"},
                                { title: "Cap Total"},
                                { title: "Location"},
                                { title: "Devicetype"},
                                { title: "Comment"},
                                { title: "Status"},
                                { title: "Edit"}
                            ],
                            "pageLength": 150

                        } );
                    }
                });
            } );
        </script>
        @endpush
@endsection
