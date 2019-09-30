@extends('layouts.app')

@section('title', 'All Backhauls')

@section('content')
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="{{ route('backhaul.create') }}">
                            <span class="fa fa-plus-square"></span> Add
                        </a>
                        <div class="row"></div>

                        <div class="dataTable_wrapper" id="devices-datatable-div">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="backhauls-datatable" role="grid" aria-describedby="dataTables-example_info">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @push('scripts')
            <script>
                $(document).ready(function() {

                    $.ajax({
                        url: "/backhaul/ajax/all",                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function (dataSet)          //on receive of reply
                        {
                            $('#backhauls-datatable').DataTable( {
                                colReorder: true,
                                dom: 'Blfrtip',
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                                ],
                                data: dataSet,
                                pageLength: 100,
                                columns: [
                                    { title: "ID"},
                                    { title: "From Location"},
                                    { title: "To Location"},
                                    { title: "Description"},
                                    { title: "Interface Name"},
                                    { title: "Primary ?"},
                                    { title: "Threshold "},
                                    { title: "TX - Speed "},
                                    { title: "RX - Speed  "},
                                    { title: "Max TX"},
                                    { title: "Max RX"},
                                    { title: "Last Update"},
                                    { title: "Edit"},
                                    { title: "Delete"},
                                ]
                            } );
                        }
                    });
                } );
            </script>
        @endpush‹
@endsection
