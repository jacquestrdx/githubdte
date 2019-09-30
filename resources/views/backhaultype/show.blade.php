@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong>{{$bwstaff->name}}</strong></div>

                    <div class="panel-body">
                        <table class="table hover">

                            <tr>
                                <td> Name</td>
                                <td> {{ $bwstaff->name }}</td>
                            </tr>
                            <tr>
                                <td> Surname</td>
                                <td>{{$bwstaff->surname}}</td>
                            </tr>
                            <tr>
                                <td>Cell Number</td>
                                <td>{{$bwstaff->cellnum}}</td>
                            </tr>
                            <tr>
                                <td>Cell Number 2</td>
                                <td>{{$bwstaff->cellnum2}}</td>
                            </tr>

                            <tr>
                                <td>Email</td>
                                <td>{{$bwstaff->email}}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{$bwstaff->address}}</td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="{{ route('bwstaff.edit',$bwstaff->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
                                    </a>
                                </td>
                                <td>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Locations assigned to this staff member</div>
                    <div class="panel-body">
                        <table class="table hover">
                            <tr>
                                <th>Location Name</th>
                            </tr>
                            @foreach ($bwstaff->location as $locale)
                                <tr>
                                    <td><a href="{{route('location.show',$locale->id)}}">{{$locale->name}}</a></td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
