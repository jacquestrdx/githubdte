@extends('layouts.app')

@section('title', 'Show device type')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>{{$devicetype->name}}</strong></div>

                    <div class="panel-body">
                        <table class="table hover">

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
