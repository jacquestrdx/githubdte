<tr>
    <td>
        <a href="{{ route('device.edit',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Edit">
                                    <span class="fa fa-edit "></span></span>
        </a>

        <a href="{{ route('updatedevice',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title="Update">
                                    <span class="fa fa-refresh"></span></span>
        </a>

        <a href="{{ route('device.updatesoft',$device->id) }}">
                                    <span class="btn btn-primary btn-sm" title=" Download Software Update">
                                    <span class="fa fa-cloud-download"></span></span>
        </a>
        @if (\Auth::user()->user_type=="admin")

        <a href="{{ route('device.passwords',$device->id) }}">
                                    <span class="btn btn-primary btn-danger" title="Change PASSWORD">
                                    <span class="fa fa-edit"></span></span>
        </a>
        @endif
        {{--<a href="{{ route('device.reboot',$device->id) }}">--}}
        {{--<span class="btn btn-primary btn-sm" title="Reboot">--}}
        {{--<span class="fa fa-bolt"></span></span>--}}
        {{--</a>                       --}}

    </td>
    <td>
    </td>
</tr>