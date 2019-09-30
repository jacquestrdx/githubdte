@extends('layouts.app')

@section('title', 'Locations')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading">Locations
                        <a href="{{ route('location.create') }}" style="float:right">
                            <span class="fa fa-plus-square"></span> Add
                        </a>
                    </div>

                    <div class="panel-body">

                        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="locations-all" role="grid" aria-describedby="dataTables-example_info">
                           <table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function() {

            $.ajax({
                url: "/locations/all/ajax",                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#locations-all').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        pageLength: 50,
                        columns: [
                            { title: "ID"},
                            { title: "Name"},
                            { title: "Description"},
                            { title: "Long"},
                            { title: "Lat"},
                            { title: "Active PPPOE"},
                            { title: "MAX Active PPPOE"},
                            { title: "Type"},
                            { title: "Standby hours"},
                            { title: "DownDevices"},
                            { title: "Devices"},
                            { title: "Status"}

                        ]
                    } );
                }
            });
        } );
    </script>
@endpush