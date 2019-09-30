{{--<script>--}}

    {{--$(document).ready(function () {--}}

        {{--$('#dataTables-example').DataTable({--}}
            {{--responsive: true--}}
        {{--});--}}


        {{--getDownDevicesAJAX();--}}
        {{--getPppoeAJAX();--}}
        {{--getDownPowerMonsAJAX();--}}
        {{--getProblemLocationsAJAX();--}}

    {{--});--}}

{{--</script>--}}

{{--<script>--}}

    {{--$(function () {--}}
        {{--$.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {--}}

            {{--$('#chartcontainer').highcharts({--}}
                {{--chart: {--}}
                    {{--zoomType: 'x'--}}
                {{--},--}}
                {{--title: {--}}
                    {{--text: 'USD to EUR exchange rate over time'--}}
                {{--},--}}
                {{--subtitle: {--}}
                    {{--text: document.ontouchstart === undefined ?--}}
                            {{--'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'--}}
                {{--},--}}
                {{--xAxis: {--}}
                    {{--type: 'datetime'--}}
                {{--},--}}
                {{--yAxis: {--}}
                    {{--title: {--}}
                        {{--text: 'Exchange rate'--}}
                    {{--}--}}
                {{--},--}}
                {{--legend: {--}}
                    {{--enabled: false--}}
                {{--},--}}
                {{--plotOptions: {--}}
                    {{--area: {--}}
                        {{--fillColor: {--}}
                            {{--linearGradient: {--}}
                                {{--x1: 0,--}}
                                {{--y1: 0,--}}
                                {{--x2: 0,--}}
                                {{--y2: 1--}}
                            {{--},--}}
                            {{--stops: [--}}
                                {{--[0, Highcharts.getOptions().colors[0]],--}}
                                {{--[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]--}}
                            {{--]--}}
                        {{--},--}}
                        {{--marker: {--}}
                            {{--radius: 2--}}
                        {{--},--}}
                        {{--lineWidth: 1,--}}
                        {{--states: {--}}
                            {{--hover: {--}}
                                {{--lineWidth: 1--}}
                            {{--}--}}
                        {{--},--}}
                        {{--threshold: null--}}
                    {{--}--}}
                {{--},--}}

                {{--series: [{--}}
                    {{--type: 'area',--}}
                    {{--name: 'USD to EUR',--}}
                    {{--data: data--}}
                {{--}]--}}
            {{--});--}}
        {{--});--}}
    {{--});--}}
{{--</script>--}}

{{-- commented out by Eugene as discussed with Jacques --}}
{{--<script>--}}

    {{--var cytoscape = require('cytoscape');--}}

    {{--$(function () {--}}

        {{--var cy = cytoscape({--}}

            {{--container: document.getElementById('cy'), // container to render in--}}

            {{--elements: [ // list of graph elements to start with--}}
                {{--{ // node a--}}
                    {{--data: {id: 'a'}--}}
                {{--},--}}
                {{--{ // node b--}}
                    {{--data: {id: 'b'}--}}
                {{--},--}}
                {{--{ // edge ab--}}
                    {{--data: {id: 'ab', source: 'a', target: 'b'}--}}
                {{--}--}}
            {{--],--}}

            {{--style: [ // the stylesheet for the graph--}}
                {{--{--}}
                    {{--selector: 'node',--}}
                    {{--style: {--}}
                        {{--'background-color': '#666',--}}
                        {{--'label': 'data(id)'--}}
                    {{--}--}}
                {{--},--}}

                {{--{--}}
                    {{--selector: 'edge',--}}
                    {{--style: {--}}
                        {{--'width': 3,--}}
                        {{--'line-color': '#ccc',--}}
                        {{--'target-arrow-color': '#ccc',--}}
                        {{--'target-arrow-shape': 'triangle'--}}
                    {{--}--}}
                {{--}--}}
            {{--],--}}

            {{--layout: {--}}
                {{--name: 'grid',--}}
                {{--rows: 1--}}
            {{--}--}}

        {{--});--}}
    {{--});--}}

{{--</script>--}}



