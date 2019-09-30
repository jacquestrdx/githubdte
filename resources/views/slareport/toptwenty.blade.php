@extends('layouts.app')

@section('title', 'Top 20 Report')

@section('content')
    <div class="container">

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Top 20 Backhauls</div>
                <div class="panel-body">
                    <table class="table table-hover">
                       <thead>
                       <th>Location</th>
                       <th>Uplink</th>
                       <th>Type</th>
                       <th>TX Traffic</th>
                       <th>RX Traffic</th>
                       <th>Clients</th>
                       </thead>
                        <tbody>
                            @foreach($backhauls as $backhaul)
                                <tr>
                                    <td>
                                        <a href="/location/{!! $backhaul->locationid !!}">
                                            {!! $backhaul->locationname !!}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/location/{!! $backhaul->to_location_id !!}">
                                            {!! $instancebackhaul->getTo_location($backhaul->to_location_id) !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $backhaul->name !!}
                                    </td>
                                    <td>
                                        {!! $backhaul->maxtxspeed !!}
                                    </td>
                                    <td>
                                        {!! $backhaul->maxrxspeed !!}
                                    </td>
                                    <td>
                                        {!! $instancelocation->getPPPOECount($backhaul->locationid) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
       </div>



        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 55px">Top 20 Sectors
                    <div style="float:right">
                        <a  href="/report/top20ubnt" target="_blank" class="btn btn-default">Show Ubnt</a>
                        <a  href="/report/top20cambium" target="_blank" class="btn btn-default">Show Cambium</a>
                        <a  href="/report/sectors" target="_blank" class="btn btn-default">Show all Sectors</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <th>Device SSID</th>
                            <th>Device Name</th>
                            <th>Device Type</th>
                            <th>Device Location</th>
                            <th>Device Stations</th>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td>
                                        <a href="/device/{!! $device->id !!}">
                                            {!! $device->ssid !!}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/device/{!! $device->id !!}">
                                            {!! $device->name !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $device->devicetype->name !!}
                                    </td>
                                    <td>
                                        <a href="/location/{!! $device->location_id !!}">
                                            {!! $device->location->name !!}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/device/{!! $device->id !!}">
                                            {!! $device->active_stations !!}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading" style="height: 55px">Lowest 75 Sectors
                    <div style="float:right">

                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <th>Device SSID</th>
                        <th>Device Name</th>
                        <th>Device Type</th>
                        <th>Device Location</th>
                        <th>Device Stations</th>
                        </thead>
                        <tbody>
                        @foreach($lowsectors as $lowsector)
                            <tr>
                                <td>
                                    <a href="/device/{!! $lowsector->id !!}">
                                        {!! $lowsector->ssid !!}
                                    </a>
                                </td>
                                <td>
                                    <a href="/device/{!! $lowsector->id !!}">
                                        {!! $lowsector->name !!}
                                    </a>
                                </td>
                                <td>
                                    {!! $lowsector->devicetype->name !!}
                                </td>
                                <td>
                                    <a href="/location/{!! $lowsector->location_id !!}">
                                        {!! $lowsector->location->name !!}
                                    </a>
                                </td>
                                <td>
                                    <a href="/device/{!! $lowsector->id !!}">
                                        {!! $lowsector->active_stations !!}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Top20 Locations by PPPOE</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <th>
                                Location Name
                            </th>
                            <th>
                                Location Stations
                            </th>
                            <th>
                                Location Sectors
                            </th>
                            <th>
                                Location PPPOE's
                            </th>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)
                                <tr>
                                    <td>
                                        <a href="/location/{!! $location->id !!}">
                                            {!! $location->name !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $location->active_stations !!}
                                    </td>
                                    <td>
                                        {!! $instancelocation->getSectorCount($location->id) !!}
                                    </td>
                                    <td>
                                        {!! $location->active_pppoe !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Top20 Highsites with new clients</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <th>Location Name</th>
                            <th>Location New Clients for {!! $month !!}</th>
                        </thead>
                        <tbody>
                            @foreach ($pppoeclients as $pppoeclient)
                                <tr>

                                    <td>
                                        <a href="/location/{!! $pppoeclient->name !!}">
                                            {!! $pppoeclient->name !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $pppoeclient->newpppoeclients !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection
