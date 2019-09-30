

@extends('layouts.app')

@section('title', 'Edit Backhaul')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Backhaul</div>

                    <div class="panel-body">
                        <button onclick="refreshInterfaces()">
                            Refresh interfaces<span class="fa fa-refresh"></span>
                        </button>
                        {!! Form::model($backhaul,['method'=>'PATCH', 'route' => ['backhaul.update', $backhaul->id]]) !!}


                        <div class="form-group">
                            {{ Form::label('For location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('location_id', $locations, null, ['class' => 'js-example-basic-single form-control', 'id'=>'location']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('From location ', null, ['class' => 'control-label']) }}
                            {{ Form::select('to_location_id', $locations, null, ['class' => 'js-example-basic-single form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Priority 0-5 ... 0 Means Main backhaul for FROM site', null, ['class' => 'control-label']) }}
                            {{ Form::text('priority', "", null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Description', null, ['class' => 'control-label']) }}
                            {{ Form::text('description', "", null, ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Backhaul Type ', null, ['class' => 'control-label']) }}
                            {{ Form::select('backhaultype_id', $backhaultypes , null, ['class' => 'js-example-basic-single form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Interface ', null, ['class' => 'control-label']) }}
                            {{ Form::select('dinterface_id', $blank , null, ['class' => 'form-control','id'=>'interface']) }}
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <ul id="ips_div">

                                </ul>
                            </div>

                        </div>


                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).on("change", '#location', function(e) {
                var department = $(this).val();

                var e = document.getElementById("location");
                var strUser = e.options[e.selectedIndex].value;

                $.ajax({
                    type: "GET",
                    data: "",
                    url: '/getLocationInterfaces/'+ strUser,
                    dataType: 'json',
                    success: function(json) {

                        var $el = $("#interface");
                        $el.empty(); // remove old options
                        $el.append($("<option></option>")
                            .attr("value", '').text('Please Select'));
                        $.each(json, function(value, key) {
                            $el.append($("<option></option>")
                                .attr("value", value).text(key));
                        });




                    }
                });

                $.ajax({
                    type: "GET",
                    data: "",
                    url: '/location/getdevicesipsajax/' + strUser,
                    dataType: 'json',
                    success: function(json) {

                        var $el = $("#ips_div");
                        $el.empty(); // remove old options
                        $.each(json, function(value, key) {
                            $el.append($("<li>" + key + "</li>"));
                        });
                    }
                });
            });

            function refreshInterfaces(){

                var e = document.getElementById("location");
                var strUser = e.options[e.selectedIndex].value;

                $.ajax({
                    type: "GET",
                    data: "",
                    url: '/getLocationInterfaces/'+ strUser,
                    dataType: 'json',
                    success: function(json) {

                        var $el = $("#interface");
                        $el.empty(); // remove old options
                        $el.append($("<option></option>")
                            .attr("value", '').text('Please Select'));
                        $.each(json, function(value, key) {
                            $el.append($("<option></option>")
                                .attr("value", value).text(key));
                        });




                    }
                });

            }
        </script>
    @endpush
@endsection
