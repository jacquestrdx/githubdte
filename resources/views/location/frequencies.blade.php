@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">

                    <div class="panel-heading"><strong>{{$location->name}}</strong></div>
                    <div class="panel-body">
                        <table class="table-bordered table">
                            @foreach($colors as $key=>$row)
                                <tr>
                                    <td>{!! $key !!}</td>
                                    <td bgcolor="{!! $row['0'] !!}"></td>
                                    <td>{!! $row['1'] !!}</td>
                                </tr>
                            @endforeach
                        </table>
                    <div>
                        <table class="table-responsive">
                            <thead>
                                    <th>Frequency</th>
                            </thead>
                            <tbody>
                                @foreach($frequencybands as $key=>$band)
                                    <tr>
                                        <td style="width:20px">{{$key}}</td>
                                        @foreach ($band as $ssid)
                                                <td style="width:20px;">
                                                </td>
                                                @if ($ssid)
                                                    <td title="{{$ssid}} {{$colors[$ssid]['1']}}" bgcolor="{{$colors[$ssid]['0']}}" style="width:20px;">
                                                    </td>
                                                @else
                                                    <td bgcolor="" style="width:20px;"></td>
                                                @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


        </div>
    </div>
    </div>

@endsection


