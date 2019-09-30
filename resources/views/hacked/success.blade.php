@extends('layouts.app')

@section('title', 'SUCCESS')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div>
                        @if (session('notification_type'))
                            @if (session('notification_type')=="Error")
                                @if (session('status'))
                                    <div class="alert alert-danger">
                                        {{ session('status') }}
                                    </div>
                                    @php
                                        session(['status' => '']);
                                        session(['notification_type' => '']);
                                    @endphp
                                @endif
                            @endif
                        @endif

                        @if (session('notification_type'))
                            @if (session('notification_type')=="Success")
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                    @php
                                        session(['status' => '']);
                                        session(['notification_type' => '']);
                                    @endphp

                                @endif
                            @endif
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
