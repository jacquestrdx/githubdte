@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new device</div>

                <div class="panel-body">



                    {!! Form::open(['action'=>'HighsiteformController@store']) !!}

                    <div class="form-group">
                        {{ Form::label('Location ', null, ['class' => 'control-label']) }}
                        {{ Form::select('location_id', $locations,['class' =>'js-example-basic-single form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Category ', null, ['class' => 'control-label']) }}
                        {{ Form::select('highsite_visit_category_id', $categories,['class' =>'js-example-basic-single form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Ticket Nr ', null, ['class' => 'control-label']) }}
                        {{ Form::text('ticket_nr','') }}
                    </div>


                    <div class="form-group">
                        {{ Form::label('Users ', null, ['class' => 'control-label']) }}
                    </div>

                    <div class="form-group" id="users_div">
                        <div>
                            <select name="user_ids[]" id="user_select_div">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                         <span id="another_user" class="btn btn-primary btn-sm" title="Create">
                            <span class="fa fa-plus-square"></span></span> Add a technician
                    </div>

                    <div class="form-group">
                        {{ Form::label('Job Description ', null, ['class' => 'control-label']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::textarea('job_to_do','') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Job Done Description ', null, ['class' => 'control-label']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::textarea('job_done','') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Time Started ', null, ['class' => 'control-label']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('time_started','') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Time Finished ', null, ['class' => 'control-label']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::text('time_ended','') }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Additional Notes ', null, ['class' => 'control-label']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::textarea('notes','') }}
                    </div>


                <div class="form-group" id="stock_div">

                    <div>
                            {{ Form::label('Serial Number used', null, ['class' => 'control-label']) }}
                    </div>
                    <div>
                            {{ Form::text('stock_used[]','') }}
                    </div>
                    <div>
                            {{ Form::label('Stock description', null, ['class' => 'control-label']) }}
                    </div>
                    <div>
                            {{ Form::text('stock_description[]','') }}
                    </div>
                    </br>

                </div>
                <div class="form-group">
                    {{ Form::label('Stock', null, ['class' => 'control-label']) }}
                    <div id="more_stock">
                            <span style="" class="btn btn-primary btn-sm" title="Create">
                            <span class="fa fa-plus-square"></span></span> Add a stock item
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



@endsection

@push('scripts')
    <script>


        $( document ).ready(function() {
            var e = document.getElementById("user_select_div");
                $.ajax({
                    type: "GET",
                    data: "",
                    url: '/getusers/ajax',
                    dataType: 'json',
                    success: function(json) {
                        var $el = $("#user_select_div");
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
        var count = 0;

        $( "#another_user" ).click(function() {
            count = count +1;
            $.ajax({
                type: "GET",
                data: "",
                url: '/getusers/ajax',
                dataType: 'json',
                success: function(json) {
                    var $element = $("#users_div");
                    $element.append($("<div> <select id='" + count + "' name='user_ids[]'>"));
                    var $element = $("#" + count);
                    $element.append($("<option></option>")
                        .attr("value", '').text('Please Select'));
                    $.each(json, function(value, key) {
                        $element.append($("<option></option></div> ")
                            .attr("value", value).text(key));
                    });
                }
            });
        });

        $( "#more_stock" ).click(function() {
            var $element = $("#stock_div");
            $element.append($("</br>"));
            $element.append($("<div> <label for='Serial Number used' class='control-label'>Serial Number Used</label></div> <div> "));
            $element.append($("<div> <input name='stock_used[]' type='text' value=''> <div> "));
            $element.append($("<div> <label for='Stock description' class='control-label'>Stock description</label> <div> "));
            $element.append($("<div> <input name='stock_description[]' type='text' value=''> <div> "));
        });
    </script>
@endpush

