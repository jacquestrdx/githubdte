@extends('layouts.app')

@section('title', 'All interfaces')

@section('content')
        <div class="row">

            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Staff</div>

                    <div class="panel-body">
                        <table class="table hover">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th>Traffic</th>
                                <th>Recorded at</th>
                                <th>Threshhold</th>
                                <th>Max</th>
                                <th>Max at</th>


                            </tr>
                            </thead>
                            @foreach ($warnings as $warning)
                                <tr>
                                    <td>
                                        {{$warning->description}}
                                    </td>

                                    <td>
                                        {{$warning->traffic}}
                                    </td>

                                    <td>
                                        {{$warning->created_at}}
                                    </td>

                                    <td>
                                        {{round($warning->threshhold,2)}}
                                    </td>

                                    <td>
                                        {{$warning->max}}
                                    </td>

                                    <td>
                                        {{$warning->max_at}}
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection
