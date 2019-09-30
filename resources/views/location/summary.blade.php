
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
                <div class="panel-heading">{{$location->name}}</div>

                <div class="panel-body">
                                </br>
                                </br> 
                <table class="table hover">
                <tr>

                </tr>
                <?php $sum = ''; ?>
                <tr>
                    <th>Device Name</th>
                    <th>IP</th>
                    <th>Fault description</th>
                </tr>    
                 @foreach ($location->device as $device)
                <tr>
                    <td>{{$device->name}}</td>
                    <td><a href="{{ route('device.show',$device->id) }}">  {{$device->ip}}</a></td>
                    <td>
                        <ul>
                        @if (null!==(explode(',',$device->fault_description)))    
                        @foreach(explode(',',$device->fault_description) as $fault)
                           @if ($fault != "")<li>{{$fault}}</li>@endif
                        @endforeach
                        @endif
                        </ul>    
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
