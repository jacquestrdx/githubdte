@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
        <div class="row">
                        <div>
                            <a href="{{ route('job.create') }}" style="">
                                <span class="fa fa-plus-square"></span> Add
                            </a>
                        </div>

                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer" id="jobs-all" role="grid" aria-describedby="dataTables-example_info">

                            </table>
                        </div>
                    </div>
    </div>
@endsection
