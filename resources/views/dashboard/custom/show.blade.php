@extends('layouts.app')

@section('title', 'Custom Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Custom Dashboard
                    </div>

                    <div class="panel-body">

                        <div class="row"></div>


                        <table class="table-hover table table-responsive">

                            <tr>
                                <th>Dashboard Title</th><td>{!! $dashboard->title !!}</td>
                            </tr>
                            <tr><th>Items</th><td></td></tr>
                            @foreach($dashboard->items as $item)
                                <tr>
                                    <th>Type : {!! $item->type !!}</th>
                                    <td>ID : {!! $item->description !!}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>


@endsection