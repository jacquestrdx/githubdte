<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">All Pppoe Sessions

                </div>

                <div class="panel-body">

                    <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                               id="dataTables-example2" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Uptime</th>
                                <th>Concentrator</th>
                                <th>Connected</th>
                            </tr>
                            </thead>
                            @foreach ($device->pppoes as $pppoe)
                                <tr>
                                    <td>{{$pppoe->username}}</td>
                                    <td>{{$pppoe->uptime}}</td>
                                    <td>
                                        <a href="{{ route('device.show', $pppoe->device->id) }}">  {{$pppoe->device->name}}</a>
                                    </td>
                                    <td>
                                        @if ($pppoe->is_online == "1")
                                            <i class="fa fa-check-circle"
                                               aria-hidden="true"
                                               style="color:green"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>