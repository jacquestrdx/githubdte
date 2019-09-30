
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">

                <div class="panel-heading">Stats for this Wifi device

                </div>

                <div class="panel-body">

                    <div>
                        <table class="table table-striped table-bordered table-hover">
                            <tr><th>Device Model</th><td>{{$device->model}} </td></tr>
                            <tr><th>Wireless Mode</th><td>{{$device->wireless_mode}}</td></tr>
                            <tr><th>Stations</th><td>{{$device->active_stations}}</td></tr>
                            <tr><th>Device SSID</th><td>{{$device->ssid}}</td></tr>
                            <tr><th>Device Frequency</th><td>{{$device->freq}}</td></tr>
                            <tr><th>Device Channel</th><td>{{$device->channel}}</td></tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('device.mikrotik.statable')