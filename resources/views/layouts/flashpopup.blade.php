@if (session('notification_type'))
    @if (session('notification_type')=="Error")
        @if (session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @php
                session(['status' => '']);
                session(['notification_type' => '']);
            @endphp
        @endif
    @endif
@endif

@if (session('notification_type'))
    @if (session('notification_type')=="Success")
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @php
                session(['status' => '']);
                session(['notification_type' => '']);
            @endphp

        @endif
    @endif
@endif


