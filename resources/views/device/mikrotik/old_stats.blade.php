

<table class="table hover">
    <tr>
        <td> Device Name</td>
        <td> {{ $old_stats->name }}</td>
    </tr>
    <tr>
        <td>Device IP</td>
        <td>{{$old_stats->ip}}</td>
    </tr>
    <tr>
        <td>Device ASN</td>
        <td>{{$old_stats->as_number}}</td>
    </tr>


    <tr>
        <td>Device Model</td>
        <td>{{$old_stats->model}}</td>
    </tr>
    <tr>
        <td>Software version</td>
        <td>{{$old_stats->soft}}</td>
    </tr>
    <tr>
        <td>Firmware version</td>
        <td>{{$old_stats->firm}}</td>
    </tr>
    <tr>
        <td>Uptime</td>
        <td>{{$old_stats->uptime}}</td>
    </tr>
    <tr>
        <td>DNS Server</td>
        <td>{{$old_stats->dns_server}}</td>
    </tr>
    <tr>
        <td>Current Volts</td>
        <td>{{$old_stats->volts}} V</td>
    </tr>
    <tr>
        <td>Current Amps(mAh)</td>
        <td>{{$old_stats->current}} </td>
    </tr>
    <tr> @if ($old_stats->temp > 50)
            <td>Temperature</td>
            <td style="color:red;">{{$old_stats->temp}}</td>
        @else
            <td>Temperature</td>
            <td style="color:green;">{{$old_stats->temp}}</td>
        @endif
    </tr>
    <tr>
        <td>Active PPPOE Clients</td>
        @if ($old_stats->active_pppoe >= 36)
            <td style="color:red">{{$old_stats->active_pppoe}}
                / {{$old_stats->maxactivepppoe}}</td>
        @else
            <td>{{$old_stats->active_pppoe}} / {{$old_stats->maxactivepppoe}} </td>
        @endif
    </tr>
    <tr>
        <td>Cpu Load</td>
        <td>
            <progress
                    value={{round($old_stats->cpu)}} max="100"></progress> {{round($old_stats->cpu)}}
            %
        </td>
    </tr>

    <tr>
        <td>
            Default Gateway
        </td>
        <td>
            {{$old_stats->default_gateway}}
        </td>
    </tr>


    <tr>
        <td>VPN Servers enabled</td>
        <td style="color:red">
            @if ($old_stats->sstp_server == "1")
                SSTP ,
            @endif
            @if ($old_stats->pptp_server == "1")
                PPTP ,
            @endif
            @if ($old_stats->ovpn_server == "1")
                OVPN ,
            @endif
            @if ($old_stats->l2tp_server == "1")
                L2TP ,
            @endif
        </td>
    </tr>

    <tr>
        @if ($old_stats->used_memory >= 75)
            <td>Used Memory</td>
            <td style="color:red">
                <progress
                        value={{round($old_stats->used_memory)}} max="100"></progress> {{round($old_stats->used_memory)}}
                %
            </td>
        @else
            <td>Used Memory</td>
            <td style="color:green">
                <progress
                        value={{round($old_stats->used_memory)}} max="100"></progress> {{round($old_stats->used_memory)}}
                %
            </td>
        @endif
    </tr>
</table>
