@extends('layouts.app')

@section('title', 'All interfaces')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Warnings</div>

                    <div class="panel-body">

                            @foreach ($warnings as $warning)
                                <h3>{!! $warning->updated_at !!}<p>Max : {!! $warning->max !!} Mbps</p></h3>
                                <div id="{!! $warning->id !!}div">
                                </div>
                                     <a target="_blank" href="{{ route('device.mikrotik.interfaces',$warning->device_id) }}">
                                        {{$warning->description}}
                                    </a>
                            @endforeach

                        @push('head')
                                <meta http-equiv="refresh" content="30"/>
                        @endpush

                    </div>

                    @push('scripts')

                    @foreach ($warnings as $warning)
                        <script>
                            var variable{!! $warning->id !!} = new JustGage({
                                id: "{!! $warning->id !!}div",
                                value: {!! $warning->traffic !!},
                                min: 0,
                                max: "{!! $warning->threshhold !!}",
                                title: "{!! $warning->description !!}"
                            });
                        </script>
                    @endforeach

                    @endpush


                </div>
            </div>
        </div>
    </div>
@endsection
