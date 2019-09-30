@extends('layouts.app')

@section('content')
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Show</div>

                    <div class="panel-body">
                        <h1>uioasgasfsdfghasdf</h1>
                        <table>
                        <tr style="background-color:#d9534f;">
                            <td>{{$sipaccount->username }}</td>
                            <td>{{$sipaccount->shortnumber }}</td>
                            <td>{{$sipaccount->longnumber }}</td>
                            <td>{{$sipaccount->currentip }}</td>
                            <td>{{$sipaccount->status }}</td>
                            <td>{{$sipaccount->historicalip }}</td>
                            <td>{{$sipaccount->model }}</td>
                            <td>{{$sipaccount->sipserver }}</td>
                            <td>{{$sipaccount->lastupdate }}</td>
                        </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
