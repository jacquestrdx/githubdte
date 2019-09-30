@extends('layouts.app')

@section('title', 'All Custom SNMP OID')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Custom SNMP OID</div>
                    <a href="{{ route('customsnmpoid.create') }}" style="float:right">
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
                            @foreach ($customoids as $customoid)
                                <tr>
                                    <td>
                                        {!! $customoid->id !!}
                                    </td>
                                    <td>
                                        {!! $customoid->name !!}
                                    </td>
                                    <td>
                                        {!! $customoid->color !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('customsnmpoid.edit',$customoid->id) }}">
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
