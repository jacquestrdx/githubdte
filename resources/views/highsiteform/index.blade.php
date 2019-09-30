@extends('layouts.app')

@section('title', 'High Site Forms')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">High Site Forms</div>

                    <div class="panel-body">
                        <table class="table hover">
                            @if (Auth::user()->user_type=="field")
                            @else
                                <a style="align:right" href="{{ route('highsiteform.create') }}">
                                    <span style="" class="btn btn-primary btn-sm" title="Create">
                                    <span class="fa fa-plus-square"></span></span> Add a Highsite visit
                                </a>
                            @endif

                            @if ( (Auth::user()->user_type=="manager") or (Auth::user()->user_type=="admin"))
                                    <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                                        <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                               id="dataTables-example" role="grid"
                                               aria-describedby="dataTables-example_info">
                                    <thead>
                                        <th>Id</th>
                                        <th>Highsite</th>
                                        <th>Category</th>
                                        <th>Ticket Nr</th>
                                        <th>Users</th>
                                        <th>Job Description</th>
                                        <th>Job Done</th>
                                        <th>Time Started</th>
                                        <th>Time Ended</th>
                                        <th>Maintenance notes</th>
                                    </thead>
                                    <tbody>
                                    {{--@if (isset($location->highsiteforms))--}}
                                    @foreach ($highsiteforms as $highsiteform)
                                        <tr>
                                            <td>{!! $highsiteform->id !!}</td>
                                            <td>{!! $highsiteform->location->name !!}</td>
                                            <td>{!! $highsiteform->highsite_visit_category->description !!}</td>
                                            <td>{!! $highsiteform->ticket_nr !!}</td>
                                            <td>
                                                @foreach(json_decode($highsiteform->user_ids) as $user)
                                                    {!! $someuser->getName($user)  !!} ,
                                                @endforeach
                                            </td>
                                            <td>{!! $highsiteform->job_to_do !!}</td>
                                            <td>{!! $highsiteform->job_done !!}</td>
                                            <td>{!! $highsiteform->time_started !!}</td>
                                            <td>{!! $highsiteform->time_ended !!}</td>
                                            <td>{!! $highsiteform->notes !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    {{--@endif--}}
                                </table>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
