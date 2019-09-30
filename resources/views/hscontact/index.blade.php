@extends('layouts.app')

@section('title', 'High Site Contacts')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">High Site Contacts

                        <a style="float:right" href="{{ route('hscontact.create') }}">
                            <span class="fa fa-plus-square"></span> Add
                        </a>

                    </div>

                    <div class="panel-body">

                        <table class="table hover">

                            <tr>
                                <th>Contact Name</th>
                                <th>Cell number</th>
                                <th></th>
                            </tr>
                            @foreach ($hscontacts as $hscontact)
                                <tr>
                                    <td>
                                        <a href="{{ route('hscontact.show',$hscontact->id) }}">  {{$hscontact->name}}</a>

                                    </td>

                                    <td>
                                        {{$hscontact->cellnum}}
                                    </td>

                                    <td>
                                        <a href="{{ route('hscontact.edit',$hscontact->id) }}">
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
