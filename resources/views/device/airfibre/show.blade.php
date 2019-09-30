<table class="table hover">

    <tr>
        <td>TX Frequency</td>
        <td>{{$device->txfreq}}</td>
    </tr>
    <tr>
        <td>RX Frequency</td>
        <td>{{$device->rxfreq}}</td>
    </tr>

    <tr>
        <td>SSID</td>
        <td>{{$device->ssid}}</td>
    </tr>
    <tr>
        <td>
            Signal Chain 1
        </td>
        <td>
            {{$device->signal1}}
        </td>
    </tr>

    <tr>
        <td>
            Signal Chain 2
        </td>
        <td>
            {{$device->signal2}}
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