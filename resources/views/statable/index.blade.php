@extends('layouts.app')

@section('title', 'Stations')

@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

            <div class="panel panel-default">
                <div class="panel-heading">All Stations</div>

                <div class="panel-body">
                <table class="table hover">
                <div class="panel-body">
                    <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="statable-all" role="grid" aria-describedby="dataTables-example_info">

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
                    url: "/statable/all/ajax",                  //the script to call to get data
                    data: "",                        //you can insert url arguments here to pass to api.php
                                                     //for example "id=5&parent=6"
                    dataType: 'json',                //data format
                    success: function (dataSet)          //on receive of reply
                    {
                        $('#statable-all').DataTable( {
                            colReorder: true,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                            ],
                            data: dataSet,
                            pageLength: 50,
                            columns: [
                                { title: "Id" },
                                { title: "Name" },
                                { title: "Mac" },
                                { title: "Ip" },
                                { title: "Signal" },
                                { title: "Distance" },
                                { title: "Model" },
                                { title: "RATES" },
                                { title: "SSID" },
                                { title: "Status" }
                            ]
                        } );
                    }
                });
            } );
        </script>

    @endpush

@endsection
