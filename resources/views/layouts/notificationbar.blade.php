<div id="notificationbar">
<li class="dropdown">

        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
            {{--{{\Auth::user()->getNotificationCount()}}--}}
        {{--</a>--}}
        {{--<ul class="dropdown-menu" role="menu">--}}

            {{--@foreach(Auth::user()->getNotifications() as $notification)--}}
                {{--<li>--}}
                    {{--<a href="{{ route('usernotification.read',$notification->id) }}">--}}
                        {{--<i class="fa fa-btn"></i>{{$notification->notification->message}}</a>--}}
                {{--</li>--}}
            {{--@endforeach--}}

            {{--<li>--}}
                {{--<a href="{{ route('usernotification.all') }}">--}}
                    {{--<i class="fa fa-btn"></i>Mark all as read</a>--}}
                {{--</a>--}}
            {{--</li>--}}
        {{--</ul>--}}

    {{--</li>--}}
</div>
