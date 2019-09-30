@extends('layouts.app')

@section('title', 'Custom Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new Dashboard</div>

                    <div class="panel-body">
<table class="table-hover table table-responsive">

    @foreach ($dashboards as $dashboard)
        <tr>
            <td>{!! $dashboard->title !!}</td>
            <td>{!! sizeof($dashboard->items) !!} Items on dashboard</td>
            <td><a href="/dash/additem/{!! $dashboard->id !!}"> Add an item<i class="fa fa-plus"></i></a></td>
        </tr>
    @endforeach
</table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

