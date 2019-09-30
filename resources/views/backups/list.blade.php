@extends('layouts.app')

@section('title', 'BGP Peers')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">

                    <div class="panel-heading" style="font-weight:bold">
                        <strong>Device backups</strong>
                    </div>

                    <div class="panel-body">


                        <div class="row">

                            <div class="dataTable_wrapper col-md-12 col-md-offset-0">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                       id="dataTables-example" role="grid"
                                       aria-describedby="dataTables-example_info">
                                    <thead>
                                    <tr>
                                        <th>Filename</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($filenames as $file)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('device.getbackup',$file) }}">  {{$file}}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(array_key_exists("1",$filenames))
                                            <a href="/backup/compare/{{$filenames['0']}}/{{$filenames['1']}}">
                                                Compare files
                                            </a>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
