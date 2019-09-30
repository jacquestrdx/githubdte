@extends('layouts.app')

@section('title', 'Stations')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

            <div class="panel panel-default">
                <div class="panel-heading">All Stations</div>

                <div class="panel-body">
                <table class="table hover">
                <div class="panel-body">
                    <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="stations-all" role="grid" aria-describedby="dataTables-example_info">

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
