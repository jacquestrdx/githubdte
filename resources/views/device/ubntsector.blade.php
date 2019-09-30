
<tr>
    <td> Device Name</td>
    <td> {{ $device->name }}</td>
</tr>
<tr>
    <td>Device IP</td>
    <td><a href="http://{{$device->ip}}" target="_blank">{{$device->ip}}</a>
    </td>
</tr>

<tr>
    <td>Device Type</td>
    <td>{{$device->devicetype->name or ""}}</td>
</tr>

<tr>
    <td>Frequency</td>
    <td>{{$device->freq}}</td>
</tr>

<tr>
    <td>SSID</td>
    <td>{{$device->ssid}}</td>
</tr>

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

<tr>
    <td>TX power</td>
    <td>{{$device->txpower}}</td>
</tr>

<tr>
    <td>WDS</td>
    <td>@if ($device->wds == 4)Enabled @else "N/a" @endif</td>
</tr>

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

<tr>
    <td>Connected Stations</td>
    <td>{{$device->active_stations}}</td>
</tr>