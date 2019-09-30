<div class="row">
    <div class="col-md-12">
        <div class="dataTable_wrapper col-md-12 col-md-offset-0">
            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                   id="dataTables-example" role="grid"
                   aria-describedby="dataTables-example_info">
                <thead>
                <th>Name</th>
                <th>Mac Address</th>
                <th>Last IP</th>
                <th>Signal</th>
                <th>Distance</th>
                <th>Rates</th>
                <th>SSID</th>
                <th>Time Connected</th>
                <th>Model</th>
                <th>Connected</th>
                </thead>
                <tbody>
                @if (count($device->statables))
                    @foreach ($device->statables as $statable)
                        @if($statable->status == 0)
                        <tr style="color:green">
                            <td>{{$statable->name}}</td>
                            <td><a href='/statables/pergraph/{!! $statable->id !!}'>{{$statable->mac}}</a></td>
                            <td>{{$statable->ip}}</td>
                            <td>{{$statable->signal}}</td>
                            <td>{{$statable->distance}}</td>
                            <td>{{$statable->rates}}</td>
                            <td>
                                <a href="{{ route('device.show',$statable->device->id) }}">{{$statable->device->ssid or ""}}</a>
                            </td>
                            <td>{{$statable->time}}</td>
                            <td>{{$statable->model}}</td>
                            <td>
                                @if ($statable->updated_at>$formatted_date)
                                    <i class="fa fa-check-circle"
                                       aria-hidden="true"
                                       style="color:green"></i>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if($statable->status == 2)
                            <tr style="color:darkorange">
                                <td>{{$statable->name}}</td>
                                <td>{{$statable->mac}}</td>
                                <td>{{$statable->ip}}</td>
                                <td>{{$statable->signal}}</td>
                                <td>{{$statable->distance}}</td>
                                <td>{{$statable->rates}}</td>
                                <td>
                                    <a href="{{ route('device.show',$statable->device->id) }}">{{$statable->device->ssid or ""}}</a>
                                </td>
                                <td>{{$statable->time}}</td>
                                <td>{{$statable->model}}</td>
                                <td>
                                    @if ($statable->updated_at>$formatted_date)
                                        <i class="fa fa-check-circle"
                                           aria-hidden="true"
                                           style="color:green"></i>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if($statable->status == 3)
                            <tr style="color:red">
                                <td>{{$statable->name}}</td>
                                <td>{{$statable->mac}}</td>
                                <td>{{$statable->ip}}</td>
                                <td>{{$statable->signal}}</td>
                                <td>{{$statable->distance}}</td>
                                <td>{{$statable->rates}}</td>
                                <td>
                                    <a href="{{ route('device.show',$statable->device->id) }}">{{$statable->device->ssid or ""}}</a>
                                </td>
                                <td>{{$statable->time}}</td>
                                <td>{{$statable->model}}</td>
                                <td>
                                    @if ($statable->updated_at>$formatted_date)
                                        <i class="fa fa-check-circle"
                                           aria-hidden="true"
                                           style="color:green"></i>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>