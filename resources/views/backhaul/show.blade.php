@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>{{$location->name}}</strong></div>
                    <div class="panel-body">
                            @foreach($location->backhauls as $backhaul)
                            <table class="table table-hover">
                                <tr>
                                    <th width="20%">
                                        From Site
                                    </th>
                                    <td>
                                        {{$backhaul->location->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        To Site
                                    </th>
                                    <td>
                                        {{$backhaul->getTo_location($backhaul->to_location_id)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Type
                                    </th>
                                    <td>
                                        {{$backhaul->backhaultype->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        TX
                                    </th>
                                    <td>
                                        {{$backhaul->dinterface->txspeed}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        RX
                                    </th>
                                    <td>
                                        {{$backhaul->dinterface->rxspeed}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Graphs
                                    </th>
                                    <td>
                                        <a href="/dinterface/{{$backhaul->dinterface->id}}">
                                            View Graph
                                        </a>

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        MAX
                                    </th>
                                    <td>
                                        {{$backhaul->dinterface->threshhold}}
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                </div>
        </div>
    </div>
@endsection
