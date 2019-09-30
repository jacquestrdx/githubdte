@extends('layouts.app')

@section('title', 'SMTP SERVERS')

@section('content')
@foreach($devices as $device)
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">

        <div class="row col-md-offset-2">
            <p> CPU : {{$device->cpu}} </p>
        </div>

        <div class="row col-md-offset-2">
            <p> IP : <a href="{{ route("device.show",$device->id) }}">{{$device->ip}}</a>
            </p>
        </div>

        <div class="row col-md-offset-2">
            <p>
            Free Memory  :  {{$device->free_memory}} MB
            </p>
        </div>

        <div class="row col-md-offset-2">
            <p>
            Queue Count : {{$device->queue_count}}
            </p>
        </div>

        <div class="row col-md-offset-2">
            <p>
            Last Update : {{$device->lastsnmpupdate}}
            </p>
        </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endforeach

@endsection