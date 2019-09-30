<table class="table hover">

    <tr>
        <td>Frequency</td>
        <td>{{$device->freq}}</td>
    </tr>

    <tr>
        <td>SSID</td>
        <td>{{$device->ssid}}</td>
    </tr>
    <tr>
        <td>
            Channel Width
        </td>
        <td>
            {{$device->channel}}
        </td>
    </tr>

    <tr>
        <td>
            TX Power
        </td>
        <td>
            {{$device->txpower}}
        </td>
    </tr>


    <tr>
        <td>
            Model
        </td>
        <td>
            {{$device->model}}
        </td>
    </tr>

    <tr>
        <td>Connected Stations</td>
        <td>{{$device->active_stations}}</td>
    </tr>

    <tr>
        <td>Last Update</td>
        <?php

        $date = new \DateTime;
        $date->modify('-30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        if ($device->lastsnmpupdate > $formatted_date) {
            echo "<td style='color:green'>" . $device->lastsnmpupdate . "</td>";
        } else {
            echo "<td style='color:red'>" . $device->lastsnmpupdate . "</td>";
        }

        ?>

    </tr>

    <tr>
        <td>Fault</td>
    @foreach($device->faults as $fault)
        <tr>
            <td></td>
            <td>{{$fault->description}}</td>
        </tr>
        @endforeach
        </tr>

</table>