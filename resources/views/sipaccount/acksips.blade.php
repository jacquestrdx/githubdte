@extends('layouts.app')

@section('content')
<div id="wrapper">
    <div id="container">
        <div class="row">
            <div class="col-lg-12">

                <h1 class="page-header">Acknowledged Sip Accounts for {{$topsipservername->sipservername}}</h1>
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sipaccounts as $sipaccount)
                                    @if ($sipaccount->upstreamTrunk !=1)
                                        @if ($sipaccount->status_id ==3)
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
    @if(Session::has('deletemsg'))
        <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                        </div>
                        <div class="modal-body">
                            {{--Are you sure you want to delete this sip account?--}}
                            Deleted the sipaccount
                        </div>
                        <div class="modal-footer">
                            {{--{{ Form::open(array('url' => 'sipaccount/' . $sipaccount->id, 'class' => 'pull-right')) }}--}}
                            {{--{{ Form::hidden('_method', 'DELETE') }}--}}
                            {{--{{ Form::submit('Delete this account?', array('class' => 'btn btn-warning')) }}--}}
                            {{--{{ Form::close() }}--}}
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            {{--<a href="{{ route('sipaccount.destroy',$sipaccount->id) }}">--}}
                            {{--<button type="button" class="btn btn-danger">Delete Sip Account</button>--}}
                            {{--</a>--}}

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        @endif
    </div>
    <!-- /.page-wrapper -->
</div>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
        // tooltip demo
        $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        })

        // popover demo
        $("[data-toggle=popover]")
                .popover()
    </script>
@endsection