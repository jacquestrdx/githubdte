<div class="row">
    <div class="col-md-12">
        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
            @php
                if ($device->ping == "1") {
                   $API        = new RouterosAPI();
                   $API->debug = false;
                   if ($API->connect($device->ip, config('mikrotik.api_username'), config('mikrotik.api_password'))) {

                       $API->write('/system/identity/print');

                       $READ       = $API->read();
                       $device->name = $READ[0]['name'] ?? $device->name = "n/a";
                       dd($device->name);
                }
            }

            @endphp
            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                   id="dataTables-example" role="grid"
                   aria-describedby="dataTables-example_info">

                <tr>
                </tr>
                </thead>
                <tbody>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    @if ($view_name=="device.mikrotik.interfaces")
        @if (isset($array))
            @foreach ($array as $key => $row)

                <script>

                    getStats();
                    function getStats() {
                        data2 = "";
                        data2 = {!! json_encode($row) !!};
                        new Morris.Area({
                            // ID of the element in which to draw the chart.
                            element: '{!!$key !!}',
                            // Chart data records -- each entry in this array corresponds to a point on
                            // the chart.
                            data:  data2 ,
                            hideHover: "true",                        // The name of the data record attribute that contains x-values.
                            xkey: 'time',
                            // A list of names of data record attributes that contain y-values.
                            ykeys: ['txvalue','rxvalue'],
                            // Labels for the ykeys -- will be displayed when you hover over the
                            // chart.
                            labels: ['TX','RX'],
                            pointSize : 0,
                            resize : true
                        });
                    }

                </script>
            @endforeach
        @endif

@endpush