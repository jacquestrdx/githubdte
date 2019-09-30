<script>

    document.onreadystatechange = function () {
        var state = document.readyState
        if (state == 'interactive') {
            document.getElementById('foo').style.visibility="hidden";
        } else if (state == 'complete') {
            setTimeout(function(){
                document.getElementById('interactive');
                document.getElementById('foo').style.visibility="visible";
            },1000);
        }
    }

    $(document).ready(function () {
        $('#mikrotik-panel').load('{{config('url.root_url')}}/getMikrotikInterfaces/{{$device->id}}');
    });
    setInterval(function(){
        $('#mikrotik-panel').load('{{config('url.root_url')}}/getMikrotikInterfaces/{{$device->id}}');
    }, 1500) /* time in milliseconds (ie 2 seconds)*/



</script>
