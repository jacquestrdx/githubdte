
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                                    </br>                        
                        </br>                        
                        </br>
                        </br>
             
            <div class="panel panel-default">
                <div class="panel-heading">All Locations</div>

                <div class="panel-body">
                                <a href="{{ route('location.create') }}">
                                    <span class="btn btn-primary btn-sm" title="Create">
                                    <span class="fa fa-plus-square"></span></span> Add a location
                                </a>
                                </br>
                                </br>
                    <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info">
                <thead><th>Location Name</th><th>Long</th><th>Lat</th><th>Devices</th><th>Subnet</th><th>Clients</th></thead>
                @foreach ($locations as $location)
                <tr>
                    <td>
                        <a href="{{ route('location.show',$location->id) }}">{{$location->name}}</a>
                    </td>
                    <td>
                        {{$location->lng}}
                    </td> 
                    <td>
                       {{$location->lng}}
                    </td>
                    <td>
                        {{$location->device->count()}}
                    </td>
                    <td>
                        {{$location->subnet}}
                    </td>
                    <td>
                        {{$location->device->sum('active_pppoe')}}
                    </td>
                </tr>
                @endforeach
                </table>
                        </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
