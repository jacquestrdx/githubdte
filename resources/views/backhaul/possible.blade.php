@extends('layouts.app')

@section('title', 'All Possible Backhauls')

@section('content')
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="{{ route('backhaul.create') }}">
                            <span class="fa fa-plus-square"></span> Add
                        </a>
                        <div class="row"></div>

                        <div class="dataTable_wrapper" id="devices-datatable-div">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                               <thead>
                                    <tr>
                                        <th>
                                            From Site
                                        </th>
                                        <th>
                                            To Site
                                        </th>
                                        <th>
                                            Confirm
                                        </th>
                                    </tr>
                               </thead>
                            <tbody>
                            @foreach($possiblebackhauls as $possiblebackhaul)
                                    <tr>
                                        <td>
                                            {!! $possiblebackhaul->getLocationName($possiblebackhaul->from_location) !!}
                                        </td>
                                        <td>
                                            {!! $possiblebackhaul->getLocationName($possiblebackhaul->to_location) !!}
                                        </td>
                                        <td>
                                            @if (($possiblebackhaul->added_to_backhauls == 1))
                                                Confirmed
                                            @else
                                            <a href="/backhauls/possible/{!! $possiblebackhaul->id !!}">Add</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

@endsection
