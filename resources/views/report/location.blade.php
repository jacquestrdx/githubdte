@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Location Stats</div>

                <div class="panel-body">
                    <div class="dataTable_wrapper" id="stats-datatable_div-div">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                               id="stats-datatable" role="grid" aria-describedby="dataTables-example_info">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
            <script>
                $(document).ready(function() {

                    $.ajax({
                        url: "/report/location/clientsajax",                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function (dataSet)          //on receive of reply
                        {
                            $('#stats-datatable').DataTable( {
                                colReorder: true,
                                dom: 'Blfrtip',
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                                ],
                                data: dataSet,
                                columns: [
                                    { title: "ID"},
                                    { title: "Location"},
                                    { title: "Monitored Devices"},
                                    { title: "Active PPPOE"},
                                    { title: "Active Stations"},
                                    { title: "Active Hotspot Users"},
                                    { title: "Backhauls"}
                                ]
                            } );
                        }
                    });
                } );
        </script>
    @endpush
@endsection

@push('head')


@endpush
