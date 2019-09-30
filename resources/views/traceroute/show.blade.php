@extends('layouts.app')

@section('content')
<table class="table table-striped table-bordered'">
    @foreach($results as $result)
        <tr>
            {!! dd($results) !!}
        </tr>
    @endforeach

</table>

@endsection