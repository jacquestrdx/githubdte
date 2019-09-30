@extends('layouts.app')

@section('title', 'Device Types')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All Device Types
                    
                    </div>
                    <div class="panel-body">

                        <div class="dataTable_wrapper" id="clients-datatable-div">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                   id="devicetypes-datatable" role="grid" aria-describedby="dataTables-example_info">
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
                    url: "/devicetypes/ajax/all",                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function (dataSet)          //on receive of reply
                    {
                        $('#devicetypes-datatable').DataTable( {
                            colReorder: true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                            ],
                            data: dataSet,
                            columns: [
                                { title: "ID"},
                                { title: "Name"},
                            ],
                            "pageLength": 150

                        } );
                    }
                });
            } );
        </script>
    @endpush

@endsection
