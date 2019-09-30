<table class="table hover">
    <tr>
        <td>Frequency</td>
        <td>{{$device->freq}}MHz</td>
    </tr>
<tr>
    <td>Channel Width</td>
    <td>{{$device->channel}}Mhz</td>
</tr>

<tr>
    <td>SSID</td>
    <td>{{$device->ssid}}</td>
</tr>

<tr>
    <td>TX power</td>
    <td>{{$device->txpower}}</td>
</tr>


<tr>
    <td>Signal</td>
    <td>{{$device->txsignal}}</td>
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
    @foreach($faultdescriptions as $fault)
        <tr>
            <td></td>
            <td>{{$fault}}</td>
        </tr>
        @endforeach
        </tr>

</table>