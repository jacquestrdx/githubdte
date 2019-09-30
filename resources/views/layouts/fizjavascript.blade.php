
<script>
    function getOnlineFizzes(){

        var onlinefizzesint = new JustGage({
            id: "getOnlineFizzes_div",
            value: 0,
            min: 0,
            max: 1050,
            levelColors:["#ff0000","#a9d70b", "#45d70b"],
            title: "Online Fizzes"
        });

        $.ajax({
            url: '{{config('url.root_url')}}/getOnlineFizzes',                  //the script to call to get data
            data: "",                        //you can insert url arguments here to pass to api.php
                                             //for example "id=5&parent=6"
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                onlinefizzesint.refresh(data)
            }
        });

        setInterval(function() {
            $.ajax({
                url: '{{config('url.root_url')}}/getOnlineFizzes',                  //the script to call to get data
                data: "",                        //you can insert url arguments here to pass to api.php
                                                 //for example "id=5&parent=6"
                dataType: 'json',                //data format
                success: function(data)          //on receive of reply
                {
                    onlinefizzesint.refresh(data);
                }
            });
        }, 30000);

    }
</script>