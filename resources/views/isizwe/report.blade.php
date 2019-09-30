@extends('layouts.app')

@section('title', 'Stations')

@section('content')

    <div class="container">
            <h4>Created at {!! $report->created_at !!}</h4>
            <div class="col-md-12 col-md-offset-0">
                <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="fiztable" role="grid" aria-describedby="dataTables-example_info">

                    </table>
                </div>
            </div>

            <div class="col-md-12 col-md-offset-0">
                <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="devicetable" role="grid" aria-describedby="dataTables-example_info">

                    </table>
                </div>
            </div>

            <div class="col-md-12 col-md-offset-0">
                <div class="dataTable_wrapper col-md-10 col-md-offset-1">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="latencytable" role="grid" aria-describedby="dataTables-example_info">

                    </table>
                </div>
            </div>

    </div>
@endsection
