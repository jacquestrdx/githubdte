@extends('layouts.app')

@section('title', 'Devices')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-underlined">
                            <tr>
                               <th>
                                   Device IP
                               </th>
                                <th>
                                    Device Name
                                </th>
                                <th>
                                    Device Voltage
                                </th>
                            </tr>
                            @foreach($devices as $device)
                                @if ($device->volts < 23.2)
                                <tr>
                                    <td>
                                        {!! $device->ip !!}
                                    </td>
                                    <td>
                                        {!! $device->name !!}
                                    </td>
                                        <td style="color:red">
                                            {!! $device->volts !!}
                                        </td>
                                </tr>
                                @endif

                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
