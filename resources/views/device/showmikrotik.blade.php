
<tr>
    <td> Device Name</td>
    <td> {{ $device->name }}</td>
</tr>
<tr>
    <td>Device IP</td>
    <td>{{$device->ip}}</td>
</tr>
<tr>
    <td>Device ASN</td>
    <td>{{$device->as_number}}</td>
</tr>
<tr>
    <td>Device Type</td>
    <td>{{$device->devicetype->name or ""}}</td>
</tr>
<tr>
    <td>Device Location</td>
    <td>{{$device->location->name or ""}}</td>
</tr>

<tr>
    <td>Device Model</td>
    <td>{{$device->model}}</td>
</tr>
<tr>
    <td>Software version</td>
    <td>{{$device->soft}}</td>
</tr>
<tr>
    <td>Firmware version</td>
    <td>{{$device->firm}}</td>
</tr>
<tr>
    <td>Uptime</td>
    <td>{{$device->uptime}}</td>
</tr>
<tr>
    <td>DNS Server</td>
    <td>{{$device->dns_server}}</td>
</tr>
<tr>
    <td>Current Volts</td>
    <td>{{$device->volts}} V</td>
</tr>
<tr>
    <td>Current Amps(mAh)</td>
    <td>{{$device->current}} </td>
</tr>
<tr> @if ($device->temp > 50)
        <td>Temperature</td>
        <td style="color:red;">{{$device->temp}}</td>
    @else
        <td>Temperature</td>
        <td style="color:green;">{{$device->temp}}</td>
    @endif
</tr>
<tr>
    <td>Active PPPOE Clients</td>
    @if ($device->active_pppoe >= 36)
        <td style="color:red">{{$device->active_pppoe}}
            / {{$device->maxactivepppoe}}</td>
    @else
        <td>{{$device->active_pppoe}} / {{$device->maxactivepppoe}} </td>
    @endif
</tr>
<tr>
    <td>Cpu Load</td>
    <td>
        <progress
                value={{round($device->cpu)}} max="100"></progress> {{round($device->cpu)}}
        %
    </td>
</tr>
<tr>
    @if ($device->used_memory >= 75)
        <td>Used Memory</td>
        <td style="color:red">
            <progress
                    value={{round($device->used_memory)}} max="100"></progress> {{round($device->used_memory)}}
            %
        </td>
    @else
        <td>Used Memory</td>
        <td style="color:green">
            <progress
                    value={{round($device->used_memory)}} max="100"></progress> {{round($device->used_memory)}}
            %
        </td>
    @endif
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