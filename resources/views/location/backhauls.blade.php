@extends('layouts.app')
@section('content')
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">All Backhauls from/to this location</div>
                    <div class="panel-body">
                        @foreach($results as $iname=>$result)
                            <div class="row" style="margin-bottom: 20px">
                                <div class="col-md-12 col-md-offset-0">
                                    <h4>{!! $iname !!} TX to {!! $result['0'] !!} to {!! $result['1'] !!} </h4>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{!! $result['3'] !!}"
                                             aria-valuemin="0" aria-valuemax="{!! $result['4'] !!}" style="width:{!! ($result['3']/$result['4']*100) !!}%">
                                            <span class="sr-only">TX {!! $iname !!}</span>
                                        </div>
                                    </div>
                                    <h5>{!! $result['3']  !!}Mbps / {!! $result['4'] !!}Mbps</h5>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 20px">
                                <div class="col-md-12 col-md-offset-0">
                                    <h4>{!! $iname !!} RX to {!! $result['0'] !!} to {!! $result['1'] !!} </h4>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="{!! $result['2'] !!}"
                                             aria-valuemin="0" aria-valuemax="{!! $result['4'] !!}" style="width:{!! ($result['3']/$result['4']*100) !!}%">
                                            <span class="sr-only">RX {!! $iname !!}</span>
                                        </div>
                                    </div>
                                    <h5>{!! $result['3']  !!}Mbps / {!! $result['4'] !!}Mbps</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

@push('scripts')

@endpush

@endsection