{{--MAIN LOOP--}}

<table class="table table-hover table-condensed table-bordered" >
    <tr>
        <th>
            Highsite
        </th>
        <th>
            Uplink
        </th>
        <th>
            TX
        </th>
        <th>
            RX
        </th>

    </tr>

@foreach($backhauls as $backhaul)

        <tr>
        <td>
            {!! $backhaul->locationname !!}
        </td>
        <td>
            {!! $instancebackhaul->getTo_location($backhaul->to_location_id) !!}
        </td>
        <td>
                {!! $backhaul->txspeed !!} Mbps
        </td>
        <td>
                {!! $backhaul->rxspeed !!} Mbps
        </td>



    </tr>

@endforeach
</table>

