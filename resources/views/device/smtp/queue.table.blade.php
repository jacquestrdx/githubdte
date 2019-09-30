
@if (array_key_exists('0',$queue))
    <h2>No response from server</h2>
@else
    <div class="dataTable_wrapper col-md-12 col-md-offset-0">

        <table class="table table-striped table-bordered table-hover dataTable no-footer"
               id="dataTables-example" role="grid"
               aria-describedby="dataTables-example_info">

            <thead>
            @foreach ($data['headings'] as $heading)
                <th>{{$heading}}</th>
            @endforeach
            </thead>
            <tbody>
            @foreach ($data['queues'] as $queue)
                <tr>
                    <td>{{$queue['0']}}</td>
                    <td>{{$queue['1']}}</td>
                    <td>{{$queue['2']}}</td>
                    <td>{{$queue['3']}}</td>
                    <td>{{$queue['4']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endif