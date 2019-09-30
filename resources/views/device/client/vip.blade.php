
@extends('layouts.app')

@section('title', 'VIP Clients')

@section('content')

@push('head')
    <meta http-equiv="refresh" content="5">
@endpush
    <div class="container">

        <div class="row">

            <div class="col-md-12 col-md-offset-0">
                <a href="{{ url('/clients/vip/all') }}"  class="btn btn-default">Show All</a>
                <a href="{{ url('/clients/vip/online') }}"  class="btn btn-default">Show Online</a>
                <a href="{{ url('/clients/vip/offline') }}"  class="btn btn-default">Only offline</a>
                <div class="panel panel-default">

                    <div class="panel-heading">Offline VIP Clients</div>

                    <div class="panel-body">

    @foreach($devices as $device)
            <div class="col-md-6">

                <table class="table table-underlined table-bordered" @if ($device->ping == 1) style="border: solid green" @else style="border: solid red" @endif
                    <tr>
                        <th style="width:30%">Client Name</th>
                        <td><a href="/device/{!!$device->id!!}">{!! $device->name !!}</a>
                            <a style="float:right" href="{{ route('device.edit',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Client IP</th>
                        <td><a href="/device/{!!$device->id!!}">{!! $device->ip !!}</a></td>
                    </tr>

                    <tr>
                        <th>Client Reseller</th>
                        <td>{!! $device->reseller !!}</td>
                    </tr>
                    <tr>
                        <th>Client Datatill Link</th>
                            <td>
                                <a href="{!! $device->client_datatill_link !!}" target="_blank">Go to Datatill</a>
                            </td>
                    </tr>
                </table>
            </div>
    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection('content')