@extends('layouts.app')

@section('title', 'Interfaces')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Interfaces on threshold

                    </div>

                    <div class="panel-body">

                        <div class="row"></div>

                        <div class="dataTable_wrapper" id="devices-datatable-div">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="devices-datatable" role="grid" aria-describedby="dataTables-example_info">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('scripts')
        <script>
            $(document).ready(function() {

                $.ajax({
                    url: "/interfacewarnings/ajax/all",                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function (dataSet)          //on receive of reply
                    {
                        $('#devices-datatable').DataTable( {
                            colReorder: true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                            ],
                            data: dataSet,
                            columns: [
                                { title: "Device Name"},
                                { title: "Interface Name"},
                                { title: "Message"},
                                { title: "Usage"},
                                { title: "Time"}
                            ]
                        } );
                    }
                });
            } );
        </script>
    @endpush‹
@endsection
