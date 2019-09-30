<html>
<head>
    <style type="text/css">
        #container {
            max-width: 400px;
            height: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
<div id="container"></div>
<script src="sigma/sigma.min.js"></script>
<script src="sigma/sigma.parsers.json.min.js"></script>
<script>
    sigma.parsers.json('mapjson', {
        container: 'container',
        settings: {
            defaultNodeColor: '#ec5148'
        }
    });



</script>
</body>
</html>