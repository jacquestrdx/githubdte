@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight:bold"><strong></strong></div>

                    <div class="panel-body">

                        <div>
                            <a href="/stock/add/{{$job->id}}" style="">
                                <span class="fa fa-plus-square"></span> Add Stock
                            </a>
                        </div>

                        {{--<div>--}}
                            {{--<a href="{{ route('job.edit',$job->id) }}" style="">--}}
                                {{--<span class="fa fa-plus-edit"></span> Edit--}}
                            {{--</a>--}}
                        {{--</div>--}}

                        <table class="table hover">

                            <tr>
                                <th>
                                    Date
                                </th>
                                <td>
                                    {{$job->date}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    Location
                                </th>
                                <td>
                                    {{$job->location->name}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    Technician
                                </th>
                                <td>
                                    {{$job->technician}}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Reg nr
                                </th>
                                <td>
                                    {{$job->reg_nr}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    Time Spent
                                </th>
                                <td>
                                    {{$job->time_spent}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    KM
                                </th>
                                <th>
                                    {{$job->km}}
                                </th>

                            </tr>

                            <tr>
                                <th>
                                    Fault
                                </th>
                                <td>
                                    {{$job->fault_description}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    Resolution
                                </th>
                                <td>
                                    {{$job->resolution}}
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    Was Fiz live
                                </th>
                                <td>
                                    @if ($job->fiz_live == "0")
                                       Yes
                                    @else
                                        No
                                    @endif
                                </td>

                            </tr>

                            <tr>
                                <th>
                                    PI down
                                </th>
                                <td>
                                    {{$job->pi_down}}
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    PI up
                                </th>
                                <td>
                                    {{$job->pi_up}}
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    Mweb Down
                                </th>
                                <td>
                                    {{$job->mweb_down}}
                                </td>

                            </tr>
                            <tr>
                                <th>
                                    Mweb up
                                </th>
                                <td>
                                    {{$job->mweb_up}}
                                </td>

                            </tr>



                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Stock for this Job</div>
                    <div class="panel-body">
                        <table class="table hover">
                            <thead>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Serial</th>
                            </thead>
                            @foreach ($job->stocks as $stock)
                                <tr>
                                    <td>{{$stock->description}}</td>
                                    <td>{{$stock->qty}}</td>
                                    <td>{{$stock->serial}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
