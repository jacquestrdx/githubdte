@extends('layouts.app')

@section('content')
<div id="wrapper">
    <div id="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sip Servers</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All Sip Accounts
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <tr>
                                    <th>Sip Server Name</th>
                                    <th>IP</th>
                                    <th>status</th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($sipservers as $sipserver)
                                    @if ($sipserver->status =="Offline")
                                        <tr style="background-color:#d9534f;">
                                            <td>{{$sipserver->sipservername }}</td>
                                            <td>{{$sipserver->ip }}</td>
                                            <td>{{$sipserver->status }}</td>
                                        </tr>
                                    @else
                                        <tr style="background-color:#5cb85c;">
                                            <td>{{$sipserver->sipservername }}</td>
                                            <td>{{$sipserver->ip }}</td>
                                            <td>{{$sipserver->status }}</td>
                                        </tr>
                                    @endif
                                @endforeach



                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive  -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.page-wrapper -->
</div>
@endsection