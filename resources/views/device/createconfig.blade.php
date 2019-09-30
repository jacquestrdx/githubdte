@extends('layouts.app')

@section('title', 'Create a device config')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">

                        {!! Form::open(['route' => ['deviceconfig.download']]) !!}


                        <div class="form-group">
                            {{ Form::label('ConfigurePPPoE', null, ['class' => 'control-label']) }}
                            <input class="ConfigurePPPoE" type="checkbox" id="ConfigurePPPoE" name="ConfigurePPPoE" value="1" checked onchange="valueChangedPPPOE()"/>

                        </div>
                        <div class="form-group pppoe-input">
                            {{ Form::label('ClientPPPoEName ', null, ['class' => 'control-label']) }}
                            {{ Form::text('ClientPPPoEName', "PPPoEAccount@bronwisp", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group pppoe-input">
                            {{ Form::label('ClientPPPoEPassword', null, ['class' => 'control-label']) }}
                            {{ Form::text('ClientPPPoEPassword', "XXYYZZASDF", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group pppoe-input">
                            {{ Form::label('PrivateGatewayAndNetmask', null, ['class' => 'control-label']) }}
                            {{ Form::text('PrivateGatewayAndNetmask', "192.168.0.1/24", ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('UseWireless ', null, ['class' => 'control-label']) }}
                            <input class="UseWireless" type="checkbox" id="UseWireless" name="UseWireless" value="1" checked onchange="valueChangedWireless()"/>
                        </div>

                        <div class="form-group wireless-input">
                            {{ Form::label('SSID', null, ['class' => 'control-label']) }}
                            {{ Form::text('SSID', "HomeWi f-i", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group wireless-input">
                            {{ Form::label('WiFiPassword', null, ['class' => 'control-label']) }}
                            {{ Form::text('WiFiPassword', "Test12345678", ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('DHCPservBool ', null, ['class' => 'control-label']) }}
                            <input class="DHCPservBool" type="checkbox" id="DHCPservBool" name="DHCPservBool" value="1" checked onchange="valueChangedDHCP()"/>
                        </div>
                        <div class="form-group dhcp-input">
                            {{ Form::label('DHCPNetworkAndMask', null, ['class' => 'control-label']) }}
                            {{ Form::text('DHCPNetworkAndMask', "192.168.0.0/24", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group dhcp-input">
                            {{ Form::label('DHCPGateway', null, ['class' => 'control-label']) }}
                            {{ Form::text('DHCPGateway', "192.168.0.1", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group dhcp-input">
                            {{ Form::label('DHCPPoolStart', null, ['class' => 'control-label']) }}
                            {{ Form::text('DHCPPoolStart', "192.168.0.20", ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group dhcp-input">
                            {{ Form::label('DHCPPoolEnd', null, ['class' => 'control-label']) }}
                            {{ Form::text('DHCPPoolEnd', "192.168.0.254", ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::submit() }}
                        </div>

                        {{ Form::close() }}
                    <html>
                    <body>
                        <script>
                                function valueChangedPPPOE()
                                {
                                    if($('.ConfigurePPPoE').is(":checked"))
                                        $(".pppoe-input").show();
                                    else
                                        $(".pppoe-input").hide();
                                }
                                function valueChangedWireless()
                                {
                                    if($('.UseWireless').is(":checked"))
                                        $(".wireless-input").show();
                                    else
                                        $(".wireless-input").hide();
                                }
                                function valueChangedDHCP()
                                {
                                    if($('.DHCPservBool').is(":checked"))
                                        $(".dhcp-input").show();
                                    else
                                        $(".dhcp-input").hide();
                                }
                        </script>
                    </body>
                    </html>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
