
@if (!array_key_exists('queues',$data))
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
                    <td>{{$queue['Count']}}</td>
                    <td>{{$queue['Volume']}}</td>
                    <td>{{$queue['Oldest']}}</td>
                    <td>{{$queue['Newest']}}</td>
                    <td>{{$queue['To-Domain']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endif