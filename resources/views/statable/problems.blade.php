@extends('layouts.app')

@section('title', 'Stations')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                       id="dataTables-example" role="grid"
                       aria-describedby="dataTables-example_info">
                    <thead>
                        <th>Message</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($statablespecs as $problem)
                                <tr>
                                    <td>{!! $problem->type !!} {{$problem->message}}</td>
                                    <td>{{$problem->type}}</td>
                                    <td>{{$problem->statable->name}}</td>
                                    @if($problem->statable->status == 3)
                                        <td style="color:red">Out of Spec</td>
                                    @endif
                                    @if($problem->statable->status == 2)
                                        <td style="color:red">Close</td>
                                    @endif
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
