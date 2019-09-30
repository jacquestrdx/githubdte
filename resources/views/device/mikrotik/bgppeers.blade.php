<div class="row">
    <div class="col-md-12">
        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                   id="dataTables-example" role="grid"
                   aria-describedby="dataTables-example_info">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Remote IP</th>
                    <th>Remote as</th>
                    <th>State</th>
                    <th>Prefix count</th>
                    <th>Disabled</th>
                    <th>Default Originate</th>
                    <th>Uptime</th>
                    <th>Updated</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @if (count($device->bgppeers))
                    @foreach ($device->bgppeers as $bgppeer)
                        <tr>
                            <td>{{$bgppeer->name}}</td>
                            <td>{{$bgppeer->remote_address}}</td>
                            <td>{{$bgppeer->remote_as}}</td>
                            <td>{{$bgppeer->state}}</td>
                            <td>{{$bgppeer->prefix_count}}</td>
                            <td>{{$bgppeer->disabled}}</td>
                            <td>{{$bgppeer->default_originate}}</td>
                            <td>{{$bgppeer->uptime}}</td>
                            <td>{{$bgppeer->updated_at}}</td>
                            <td>
                                @if (\Auth::user()->user_type=="admin")
                                    <a class="confirm" style="color:darkred;float:right" href="/bgppeer/delete/{{$bgppeer->id}}">Delete
                                        <span class="btn btn-danger btn-sm" title="Delete">
                                        <span style="color:red" class="fa fa-minus-circle "></span></span>
                                    </a>
                                    </br>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>