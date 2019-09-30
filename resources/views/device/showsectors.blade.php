
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        </br>        
        </br>
        </br>
        </br>
  


        <div class="col-md-12 col-md-offset-0">
            
            <div class="panel panel-default">
                

                        
                        
                <div class="panel-heading">All Devices
 
                </div>

                <div class="panel-body">
                <table class="table hover">
                <div class="panel-body">
                
                <tr>
                <th>Device Name</th>
                <th>Device IP</th>
                </tr>    
<!--                @foreach ($devices as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>{{$device or ""}}</a></td>
                </tr>
                @endforeach-->
                @foreach ($sectors as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors1 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors2 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors3 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors4 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors5 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors6 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                                @foreach ($sectors7 as $key => $device)
                <tr>
                    <td>{{$key or ""}}</td>
                    <td>  {{$device or ""}}</a></td>
                </tr>
                @endforeach
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
