
@if ($interfaces['0'] == "No-response")
    <h2>No response from router</h2>
@else
    <div class="dataTable_wrapper col-md-12 col-md-offset-0">

        <table class="table table-striped table-bordered table-hover dataTable no-footer"
               id="dataTables-example" role="grid"
               aria-describedby="dataTables-example_info">

            <thead>
            <th>Name</th>
            <th>Comment</th>
            <th>Type</th>
            <th>MTU</th>
            <th>Actual MTU</th>
            <th>Last UP</th>
            <th>Last Down</th>
            <th>Running</th>
            <th>TX Speed</th>
            <th>RX Speed</th>
            </thead>
            <tbody>
            @foreach ($interfaces as $interface)
                @if ($interface['running']=="true")
                <tr>
                    <td>{{$interface['name']}}</td>
                    <td>{{$interface['comment'] or ""}}</td>
                    <td>{{$interface['type']}}</td>
                    <td>{{$interface['mtu']}}</td>
                    <td>{{$interface['actual-mtu']}}</td>
                    <td>{{$interface['last-link-up-time'] or ""}}</td>
                    <td>{{$interface['last-link-down-time'] or ""}}</td>
                    <td>{{$interface['running']}}</td>
                    @if ($interface['running'] == "true")
                        <td>{{round(($interface['tx-speed']/1024/1024),2)}}M</td>
                        <td>{{round(($interface['rx-speed']/1024/1024),2)}}M</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                </tr>
                @else
                    <tr style="color:red">
                        <td>{{$interface['name']}}</td>
                        <td>{{$interface['comment'] or ""}}</td>
                        <td>{{$interface['type']}}</td>
                        <td>{{$interface['mtu']}}</td>
                        <td>{{$interface['actual-mtu']}}</td>
                        <td>{{$interface['last-link-up-time'] or ""}}</td>
                        <td>{{$interface['last-link-down-time'] or ""}}</td>
                        <td>{{$interface['running']}}</td>
                        @if ($interface['running'] == "true")
                            <td>{{round(($interface['tx-speed']/1024/1024),2)}}M</td>
                            <td>{{round(($interface['rx-speed']/1024/1024),2)}}M</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

    </div>
@endif