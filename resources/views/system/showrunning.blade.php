@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Running Scripts
                </div>
                @if (\Auth::user()->user_type=="admin")
                    <div class="panel-body">
                        <h3>Details about this Task</h3>
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Job</th>
                                    <th>Time Started</th>
                                </tr>
                            </thead>
                            @foreach($results as $key=> $result)
                                <tr><td>{!! $key !!}</td>
                                    @foreach($result as $value)
                                        <td>{!! $value !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
