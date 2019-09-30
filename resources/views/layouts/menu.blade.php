<!-- Fixed navbar -->

<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header" syle="">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">DTE</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            @if (Auth::guest())
            @else
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li><a href="{{ url('/blackboard') }}">Blackboard Dashboard</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Devices
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/device') }}">All Devices</a></li>
                            <li><a href="{{ url('/devicetype') }}">Device Types</a></li>
                            <li><a href="/devices/import">Import Devices (Beta)</a></li>
                            <li><a href="/devices/secure">Secure Customer Mikrotik Router</a></li>



                            <hr>

                            <li class="dropdown-submenu">
                                <a class="test" tabindex="-1" href="#">Switches/Routers<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/mikrotik/ips') }}">All IP adresses</a></li>
                                    <li><a href="{{ url('/neighbor') }}">All neighbors</a></li>
                                    <li><a href="{{ url('/backupstatus') }}">Device Backups</a></li>
                                    <li><a href="{{ url('/interfacelogs/index') }}">Interfacelogs</a></li>
                                    {{--<li><a href="{{ route('device.IndexMikrotiks') }}">Show mikrotik routers</a></li>--}}
                                    <li><a href="{{ url('/interfacewarnings') }}">Interfaces on threshold</a></li>
                                    <li><a href="{{ url('/deviceconfig/create') }}">Generate Config</a></li>

                                </ul>
                            </li>

                            <li class="dropdown-submenu">
                                <a class="test" tabindex="-1" href="#">UBNT<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/showallstations">Stations</a></li>
                                </ul>
                            </li>



                            <hr>
                            <li>
                                <a href="{{ route('device.create') }}">
                                    <span class="fa fa-plus-square"></span> Add a device
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">High Sites
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/location') }}">Locations</a></li>
                            <li><a href="{{ url('/backhaul') }}">Backhauls</a></li>
                            <li><a href="{{ url('/backhauls/possible') }}">Possible Backhauls</a></li>
                            <li><a href="{{ url('/backhaultype') }}">Backhaul types</a></li>
                            <li><a href="{{ url('/hscontact') }}">High Site Contacts</a></li>
                            <li><a href="{{ url('/highsiteform') }}">High Site Form</a></li>
                            <li><a href="{{ url('/devicemap') }}">Online Map</a></li>
                            <li><a href="{{ url('/networkmap') }}">Backhaul Map</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reporting
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/devices/notification_log') }}">Device Outages Log</a></li>
                            <li><a href="{{ url('/faultreport/nosnmp') }}">Snmp not working</a></li>
                            <li><a href="{{ url('/faultreport') }}">Fault Report</a></li>
                            <li><a href="{{ url('/devices/latencies') }}">Bad latency Report</a></li>

                            <li><a href="{{ url('/highsitereport/stock/detailed') }}">Detailed Location Stock Report</a></li>
                            <li><a href="{{ url('/report/top20') }}">Top 20 Report</a></li>
                            <li><a href="{{ url('/highsitereport/stock/quick') }}">Quick Location Stock Report</a></li>
                            <li><a href="{{ url('/reports/devices/month') }}">Uptime reporting per device</a></li>
                            <li><a href="{{ url('/reports/highsites/month') }}">Uptime reporting per Highsite</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Clients
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/pppoeclient') }}">All PPPOE's</a></li>
                            <li><a href="{{ url('/statable') }}">Stations</a></li>
                            <li><a href="{{ url('/clients/vip/offline') }}">VIP Clients</a></li>
                            <li><a href="{{ url('/clients/notification_log') }}">Client Outages Log</a></li>

                        </ul>
                    </li>


                    <li><a href="{{ url('/job') }}"> Jobs</a></li>





                    @endif


                    @if (Auth::guest())
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">BGP
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/bgppeers') }}">BGP Peers</a></li>
                                <li><a href="{{ url('/bgppeersoffline') }}">Down BGP Peers</a></li>
                            </ul>
                        </li>
                    {{--Discontinued for now--}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                                {{--Task Manager ( {{sizeof(\Auth::user()->tasks)}} )--}}
                                {{----}}
                                {{--<span class="caret"></span>--}}
                            {{--</a>--}}
                            {{--<ul class="dropdown-menu" role="menu">--}}
                                {{--<li><a href="{{ url('/task') }}">View all Tasks</a></li>--}}
                                {{--<li><a href="{{ url('/task/create') }}">Create a task</a></li>--}}
                                {{--<li><a href="{{ url('/project') }}">View all Projects</a></li>--}}
                                {{--<li><a href="{{ url('/project/create') }}">Create a Project</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}


                    @endif


                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                </ul>
                @else
                    <li><a href="{{ url('/inbox') }}"><i class="fa fa-btn fa-envelope"></i>Inbox ( {!! \Auth::user()->getMessageCount() !!} )</a></li>

                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div id="navbar-not"></div>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            @if (\Auth::user()->user_type=="admin")
                                <li><a href="{{ url('/user') }}"><i class="fa fa-btn fa-user"></i>Users</a></li>
                                <li><a href="{{ url('/deviceaudit') }}"><i class="fa fa-btn fa-wrench"></i>Device Audit log</a></li>
                                <li><a href="{{ url('/locationaudit') }}"><i class="fa fa-btn fa-wrench"></i>Location Audit log</a></li>
                                <li><a href="{{ url('/system') }}"><i class="fa fa-btn fa-wrench"></i>System</a></li>
                                <li><a href="{{ url('/systems/showrunning') }}"><i class="fa fa-btn fa-wrench"></i>Show running Scripts</a></li>
                            @endif

                        </ul>
                    </li>
                    </ul>
                @endif

        </div><!--/.nav-collapse -->
    </div>
</nav>

    

