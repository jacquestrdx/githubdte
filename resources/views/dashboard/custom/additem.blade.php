@extends('layouts.app')

@section('title', 'Custom Dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new Dashboard</div>

                    <div class="panel-body">

                        {!! Form::open(['action'=>'DashboardController@storeItem'],$dashboard->id) !!}

                        <div class="form-group">
                            {{ Form::label('Description ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::text('description', null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Graph type ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::select('type',  ["interface"], null, ['class' => 'form-control']) }}
                        </div>


                        <div class="form-group">
                            {{ Form::hidden('dashboard_id', $dashboard->id, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Select location ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::select('location_id', $locations, null, ['class' => 'js-example-basic-single form-control','id'=> 'location']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Select device ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::select('device_id', [""], null, ['class' => 'form-control', 'id' => "devices"]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Select interface ', null, ['class' => 'control-label col-md-3']) }}
                            {{ Form::select('type', [""], null,['id'=>'interfaces','class' => 'form-control']) }}
                        </div>

                        <div class="col-md-1 col-md-offset-3">
                            {!! Form::submit("Create", ['class' => 'btn btn-default']) !!}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        $(document).on("change", '#location', function(e) {
            var department = $(this).val();

            var e = document.getElementById("location");
            var strUser = e.options[e.selectedIndex].value;

            $.ajax({
                type: "GET",
                data: "",
                url: '/location/getdevices/' + strUser,
                dataType: 'json',
                success: function(json) {

                    var $el = $("#devices");
                    $el.empty(); // remove old options
                    $el.append($("<option></option>")
                        .attr("value", '').text('Please Select'));
                    $.each(json, function(value, key) {
                        $el.append($("<option></option>")
                            .attr("value", value).text(key));
                    });
                }
            });
        });
        $(document).on("change", '#devices', function(e) {
            var department = $(this).val();

            var e = document.getElementById("devices");
            var strUsers = e.options[e.selectedIndex].value;

            $.ajax({
                type: "GET",
                data: "",
                url: '/getdeviceinterfaces/' + strUsers,
                dataType: 'json',
                success: function(json) {

                    var $el = $("#interfaces");
                    $el.empty(); // remove old options
                    $el.append($("<option></option>")
                        .attr("value", '').text('Please Select'));
                    $.each(json, function(value, key) {
                        $el.append($("<option></option>")
                            .attr("value", value).text(key));
                    });
                }
            });
        });


    </script>
@endpush