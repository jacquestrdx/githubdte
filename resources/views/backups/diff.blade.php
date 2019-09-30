@extends('layouts.app')

@section('title', 'BGP Peers')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading" style="font-weight:bold">
                        <strong>Device backups</strong>
                    </div>

                    <div class="panel-body">


                        <div class="row">

                            @php
                                echo $diff->Render($renderer);

                            @endphp


                        </div>
            </div>
        </div>
    </div>
</div>

@endsection
