
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
                <a href="{{ url('/client') }}"  class="btn btn-default">List all</a>
                <div class="panel panel-default">

                    <div class="panel-heading">Offline VIP Clients</div>

                    <div class="panel-body">

    @foreach($clients as $client)
            <div class="col-md-6">

                <table class="table table-underlined table-bordered" @if ($client->ping == 1) style="border: solid green" @else style="border: solid red" @endif
                    <tr>
                        <th style="width:30%">Client Name</th>
                        <td><a href="/client/{!!$client->id!!}">{!! $client->name !!}</a>
                            <a style="float:right" href="{{ route('client.edit',$client->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Client IP</th>
                        <td><a href="/client/{!!$client->id!!}">{!! $client->ip !!}</a></td>
                    </tr>

                    <tr>
                        <th>Client Reseller</th>
                        <td>{!! $client->reseller !!}</td>
                    </tr>
                    <tr>
                        <th>Cap Used</th>
                        @if($client->radius_usage > $client->radius_cap)
                            <td style="color:red">{!! round(($client->radius_usage/1024),2) !!} GB</td>
                        @else
                            <td style="color:green">{!! round(($client->radius_usage/1024),2) !!} GB</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Cap Total</th>
                        @if($client->radius_usage > $client->radius_cap)
                            <td style="color:red">{!! round(($client->radius_cap/1024),2) !!} GB</td>
                        @else
                            <td style="color:green">{!! round(($client->radius_cap/1024),2) !!} GB</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Last down</th>
                        <td>{!! $client->lastdown !!}</td>
                    </tr>
                <tr>
                    <th>Comment</th>
                    <td>{!! $client->comment !!}</td>
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