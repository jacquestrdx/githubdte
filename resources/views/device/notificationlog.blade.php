@extends('layouts.app')

@section('title', 'Outages')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Outages
                        <a href="{{ route('device.create') }}" style="float:right">
                            <span class="fa fa-plus-square"></span> Add
                        </a>
                    </div>

                    <div class="panel-body">
                        <div class="dataTable_wrapper" id="devices-datatable-div">
                            <table class="display nowrap table table-striped table-bordered table-hover dataTable no-footer"
                                   id="notifications-datatable" role="grid" aria-describedby="dataTables-example_info">
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
                        url: "/outages/log/ajax",                  //the script to call to get data
                        data: "",                        //you can insert url arguments here to pass to api.php
                                                         //for example "id=5&parent=6"
                        dataType: 'json',                //data format
                        success: function (dataSet)          //on receive of reply
                        {
                            $('#notifications-datatable').DataTable( {
                                colReorder: true,
                                dom: 'Blfrtip',
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                                ],
                                data: dataSet,
                                columns: [
                                    { title: "Device Name"},
                                    { title: "Message"},
                                    { title: "Time"},
                                ]
                            } );
                        }
                    });
                } );
            </script>
        @endpush

        @push('body')
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
         @endpush


@endsection
