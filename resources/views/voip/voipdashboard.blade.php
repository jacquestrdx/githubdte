@extends('layouts.app')

@section('content')
<div id="wrapper">
    <div class="container" >
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        @foreach ($sipservers as $sipserver)
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{$sipserver->sipservername}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-tasks fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{$sipserver->getOnlineSipAccounts()}}</div>
                                                <div>Online Sip Accounts</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/sipaccount/onlinesips/{{$sipserver->id}}">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-tasks fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{$sipserver->getAckSipAccounts()}}</div>
                                                <div>Acknowledged Sip Accounts</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/sipaccount/acksips/{{$sipserver->id}}">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-tasks fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{$sipserver->getOfflineSipAccounts()}}</div>
                                                <div>Offline Sip Accounts</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/sipaccount/offlinesips/{{$sipserver->id}}">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
    @endforeach
    <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All Upstream Trunks
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Trunk Name</th>
                                    <th>Sip Server</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Last Online</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sipaccounts as $sipaccount)
                                    @if($sipaccount->upstreamTrunk ==1)
                                        @if ($sipaccount->status_id ==2)
                                            <tr style="background-color:#d9534f;">
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @elseif($sipaccount->status_id ==3)
                                            <tr style="background-color:#f0ad4e;">
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @else
                                            <tr style="background-color:#5cb85c;">
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @endif
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
                                    <th>Username</th>
                                    <th>Short Number</th>
                                    <th>Long Number</th>
                                    <th>Current IP</th>
                                    <th>Historical IP</th>
                                    <th>Model</th>
                                    <th>Sip Server</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Last Online</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sipaccounts as $sipaccount)
                                    @if($sipaccount->upstreamTrunk ==0)
                                        @if ($sipaccount->status_id ==2)
                                            <tr style="background-color:#d9534f;">
                                                <td>{{$sipaccount->username }}</td>
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->longnumber }}</td>
                                                <td>{{$sipaccount->currentip }}</td>
                                                <td>{{$sipaccount->historicalip }}</td>
                                                <td>{{$sipaccount->model }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @elseif($sipaccount->status_id ==3)
                                            <tr style="background-color:#f0ad4e;">
                                                <td>{{$sipaccount->username }}</td>
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->longnumber }}</td>
                                                <td>{{$sipaccount->currentip }}</td>
                                                <td>{{$sipaccount->historicalip }}</td>
                                                <td>{{$sipaccount->model }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @else
                                            <tr style="background-color:#5cb85c;">
                                                <td>{{$sipaccount->username }}</td>
                                                <td>{{$sipaccount->shortnumber }}</td>
                                                <td>{{$sipaccount->longnumber }}</td>
                                                <td>{{$sipaccount->currentip }}</td>
                                                <td>{{$sipaccount->historicalip }}</td>
                                                <td>{{$sipaccount->model }}</td>
                                                <td>{{$sipaccount->sipserver->sipservername }}</td>
                                                <td>{{$sipaccount->status->description }}</td>
                                                <td>{{$sipaccount->lastupdate }}</td>
                                                <td>{{$sipaccount->lastonline }}</td>
                                            </tr>
                                        @endif
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
    <!-- /#container -->
</div>
<!-- /#page-wrapper -->

@endsection

{{--<!-- jQuery -->--}}
{{--<script src="/bower_components/jquery/dist/jquery.min.js"></script>--}}

{{--<!-- Bootstrap Core JavaScript -->--}}
{{--<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>--}}

{{--<!-- Metis Menu Plugin JavaScript -->--}}
{{--<script src="/bower_components/metisMenu/dist/metisMenu.min.js"></script>--}}

{{--<!-- DataTables JavaScript -->--}}
{{--<script src="/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>--}}
{{--<script src="/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>--}}

{{--<!-- Custom Theme JavaScript -->--}}
{{--<script src="/dist/js/sb-admin-2.js"></script>--}}

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
{{--<script>--}}
    {{--$(document).ready(function() {--}}
        {{--$('#dataTables-example').DataTable({--}}
            {{--responsive: true--}}
        {{--});--}}
    {{--});--}}
{{--</script>--}}
