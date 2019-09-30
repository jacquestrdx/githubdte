@extends('layouts.app')

@section('title', 'All Staff')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Staff</div>

                    <div class="panel-body">
                        <table class="table hover">
                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>User Email address</th>
                                <th>User Type</th>
                            </tr>
                            </thead>
                            @foreach ($bwstaffs as $bwstaff)
                                <tr>
                                    <td>
                                        <a href="{{ route('bwstaff.show',$bwstaff->id) }}">  {{$bwstaff->name}}</a>

                                    </td>


                                    <td>
                                        {{$bwstaff->cellnum}}
                                    </td>

                                    <td>
                                        <a href="{{ route('bwstaff.edit',$bwstaff->id) }}">
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
