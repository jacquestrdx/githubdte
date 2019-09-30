@extends('layouts.app')

@section('content')
    @push('head')

    @endpush
    <div id="foo"></div>
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Device {{$device->name}} info
                </div>
                @if(isset($charts))
                    @foreach($charts["chart"] as $key => $chart)
                                <div class="chart"><h3 style="margin-left: 20px">{!! $charts["interface"][$key] !!}</h3></div>
                                <div class="chart">
                                    {!! $chart->container() !!}
                                </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if(isset($charts))
        @foreach($charts["chart"] as $chart)
            <div class="chart">
                {!! $chart->script() !!}
            </div>
        @endforeach
    @endif
@endpush