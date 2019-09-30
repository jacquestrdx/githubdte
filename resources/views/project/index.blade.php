@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Projects</div>
                <a href="{{ url('/project/') }}"  class="btn btn-default">All projectss</a>
                <a href="{{ url('/myproject/') }}"  class="btn btn-default">My Projects </a>

                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <th>Name</th>
                            <th>Descripton</th>
                            <th>Due Date</th>
                            <th>Completed</th>
                            <th>Notes</th>
                        </thead>
                        <tbody>
                            @foreach ($projects as $row)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('project.show',$row->id) }}">{{$row->name}}</a>
                                                    </td>
                                                    <td>{{$row->description}}</td>
                                                    <td>{{$row->due_date}}</td>
                                                    <td>{{$row->completed}}</td>
                                                    <td>{{$row->notes}}</td>
                                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
