@extends('layouts.app')

@section('title', 'All Backhaul types')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Backhaul types</div>
                    <a href="{{ route('backhaultype.create') }}" style="float:right">
                        <span class="fa fa-plus-square"></span> Add
                    </a>
                    <div class="panel-body">
                        <table class="table hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Edit</th>
                            </tr>
                            </thead>
                            @foreach ($backhaultypes as $backhaultype)
                                <tr>
                                    <td>
                                        {!! $backhaultype->id !!}
                                    </td>
                                    <td>
                                        {!! $backhaultype->name !!}
                                    </td>
                                    <td>
                                        {!! $backhaultype->color !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('backhaultype.edit',$backhaultype->id) }}">
                                            <span class="btn btn-primary btn-sm" title="Edit">
                                            <span class="fa fa-edit "></span></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
