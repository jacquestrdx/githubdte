@if ($device->devicetype->sub_type=="wireless")
    <i class="fa fa-wifi"></i>
@endif
@if ($device->devicetype->sub_type=="pm")
    <i class="fa fa-bolt"></i>
@endif
@if ($device->devicetype->sub_type=="switch")
    <i class="fa fa-toggle-on" aria-hidden="true"></i>
@endif
@if ($device->devicetype->sub_type=="router")
    <i class="fa fa-server" aria-hidden="true"></i>
@else
@endif

