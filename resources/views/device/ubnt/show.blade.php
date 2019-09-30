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
        Model
    </td>
    <td>
        {{$device->model}}
    </td>
</tr>
    <tr>
        <td>Software Version</td>
        <td>{{$device->soft}}</td>
    </tr>

@if ($device->devicetype_id != "22")
@if ($device->devicetype_id =="2")
<tr>
    <td>Software Version</td>
    <td>{{$device->soft}}</td>
</tr>
<tr>
    <td>CCQ</td>
    <td>
        <progress
                value={{$device->avg_ccq}} max="100"></progress> {{$device->avg_ccq}}
        %
    </td>
</tr>
@endif
@endif

<tr>
    <td>TX power</td>
    <td>{{$device->txpower}}</td>
</tr>

@if (($device->devicetype_id == "10") OR ($device->devicetype_id == "11"))

        <tr>
            <td>Signal</td>
            <td>{{$device->txsignal}}</td>
        </tr>

    @endif

<tr>
    <td>WDS</td>
    <td>@if ($device->wds == 4)Enabled @else "N/a" @endif</td>
</tr>

@if ($device->devicetype_id != "22")

<tr>
    <td>Airmax Capacity</td>
    <td>
        <progress
                value={{$device->airmaxc}} max="100"></progress> {{$device->airmaxc}}
        %
    </td>
</tr>



<tr>
    <td>Airmax Quality</td>
    <td>
        <progress
                value={{$device->airmaxq}} max="100"></progress> {{$device->airmaxq}}
        %
    </td>

</tr>

@endif
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
        <td>Possible Faults</td>
    @foreach($device->faults as $fault)
        <tr>
            <td></td>
            <td>{{$fault->description}}</td>
        </tr>
        @endforeach
        </tr>

</table>