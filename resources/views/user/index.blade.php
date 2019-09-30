
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">All Users

                </div>

                    <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                        <table class="table table-striped table-bordered table-responsive table-hover dataTable no-footer"
                               id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                            <th>User Name</th>
                            <th>User Email address</th>
                            <th>User Type</th>
                            <th>Edit</th>
                            <th>Verified</th>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->user_type}}</td>
                                    @if (\Auth::user()->user_type=="admin")
                                        <td>
                                            <a href="{{ route('user.edit', $user->id) }}">Edit</a>
                                        </td>
                                        <td>
                                            @if($user->verified ==0)
                                                <a href="{{ url('/users/verify/'.$user->id) }}">Validate</a>
                                            @else
                                                <a href="{{ url('/users/deverify/'.$user->id) }}">Invalidate</a>
                                            @endif
                                        </td>
                                        @else
                                        <td>
                                        </td>
                                    @endif
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
