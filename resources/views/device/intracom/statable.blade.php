<div class="row">
    <div class="col-md-12">
        <div class="dataTable_wrapper" id="devices-datatable-div">
            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                   id="statable-datatable" role="grid" aria-describedby="dataTables-example_info">
            </table>
        </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "/statables/device/"+{!! $device->id !!},                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function (dataSet)          //on receive of reply
                {
                    $('#statable-datatable').DataTable( {
                        colReorder: true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print','colvis'
                        ],
                        data: dataSet,
                        "pageLength": 150,
                        columns: [
                            { title: "Mac"},
                            { title: "IP"},
                            { title: "Distance"},
                            { title: "RX Rssi"},
                            { title: "TX Rssi"},
                            { title: "RX Snr"},
                            { title: "TX Snr "},
                            { title: "RX Rssi/Snr"},
                            { title: "TX Rssi/Sn "},
                            { title: "RX Qm"},
                            { title: "TX Qm "},
                            { title: "RX Util"},
                            { title: "Max RX Util"},
                            { title: "TX Util"},
                            { title: "Max TX Util"},
                            { title: "Time connected"},
                            { title: "State"},
                        ]
                    } );
                }
            });
        } );
    </script>
@endpush

@push('head')
    <style>

    </style>
@endpush
