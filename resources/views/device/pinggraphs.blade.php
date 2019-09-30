@extends('layouts.app')

@section('title', 'Pings')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Ping Graphs</div>

        <h2></h2>
            <div class="chart_1">
                {!! $ping_chart->container() !!}
            </div>
            <div>
                {!! $availibilty_ping_chart->container() !!}
            </div>
        </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if($ping_chart_render)
            {!! $ping_chart->script() !!}
        @endif
        @if($availibilty_ping_chart_render)
            {!! $availibilty_ping_chart->script() !!}
        @endif
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datepicker();
            });
        </script>
    @endpush
    @push('head')
    @endpush
@endsection