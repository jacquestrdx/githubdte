
@extends('layouts.app')

@section('content')

    <!-- saved from url=(0078)http://visjs.org/examples/network/layout/hierarchicalLayoutWithoutPhysics.html -->
    <h1>Hierarchical Layout</h1>

    <div id="network"><div class="vis-network" tabindex="900" style="position: relative; overflow: hidden; touch-action: pan-y; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 100%; height: 100%;"><canvas style="position: relative; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 100%; height: 100%;" width="1000" height="400"></canvas></div><div class="vis-configuration-wrapper"><div class="vis-configuration vis-config-item vis-config-s0"></div><div class="vis-configuration vis-config-item vis-config-s0"><div class="vis-configuration vis-config-header">layout</div></div><div class="vis-configuration vis-config-item vis-config-s2"><div class="vis-configuration vis-config-label vis-config-s2"><i><b>hierarchical:</b></i></div></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">enabled:</div><input type="checkbox" class="vis-configuration vis-config-checkbox"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">levelSeparation:</div><input class="vis-configuration vis-config-range" type="range" min="20" max="500" step="5"><input class="vis-configuration vis-config-rangeinput"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">nodeSpacing:</div><input class="vis-configuration vis-config-range" type="range" min="20" max="500" step="5"><input class="vis-configuration vis-config-rangeinput"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">treeSpacing:</div><input class="vis-configuration vis-config-range" type="range" min="20" max="500" step="5"><input class="vis-configuration vis-config-rangeinput"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">blockShifting:</div><input type="checkbox" class="vis-configuration vis-config-checkbox"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">edgeMinimization:</div><input type="checkbox" class="vis-configuration vis-config-checkbox"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">parentCentralization:</div><input type="checkbox" class="vis-configuration vis-config-checkbox"></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">direction:</div><select class="vis-configuration vis-config-select"><option value="UD">UD</option><option value="DU">DU</option><option value="LR">LR</option><option value="RL">RL</option></select></div><div class="vis-configuration vis-config-item vis-config-s3"><div class="vis-configuration vis-config-label vis-config-s3">sortMethod:</div><select class="vis-configuration vis-config-select"><option value="hubsize">hubsize</option><option value="directed">directed</option></select></div></div></div>
    @push('scripts')
        <script>
        var data = {
            nodes: {!! $nodes !!},
            edges: {!! $edges !!}
        };
        // create a network
        var container = document.getElementById('network');
        var options = {
            layout: {
                hierarchical: {
                    direction: "UD",
                    sortMethod: "directed"
                }
            },
            interaction: {dragNodes :false},
            physics: {
                enabled: false
            },
            configure: {
                filter: function (option, path) {
                    if (path.indexOf('hierarchical') !== -1) {
                        return true;
                    }
                    return false;
                },
                showButton:false
            }
        };
        var network = new vis.Network(container, data, options);

        </script>
@endpush

@endsection
