@push('styles')
.panel-green {
border-color: #5cb85c;
}

.panel-green .panel-heading {
border-color: #5cb85c;
color: #fff;
background-color: #5cb85c;
}

.panel-green a {
color: #5cb85c;
}

.panel-green a:hover {
color: #3d8b3d;
}

.panel-red {
border-color: #d9534f;
}

.panel-red .panel-heading {
border-color: #d9534f;
color: #fff;
background-color: #d9534f;
}

.panel-red a {
color: #d9534f;
}

.panel-red a:hover {
color: #b52b27;
}

.panel-yellow {
border-color: #f0ad4e;
}

.panel-yellow .panel-heading {
border-color: #f0ad4e;
color: #fff;
background-color: #f0ad4e;
}

.panel-yellow a {
color: #f0ad4e;
}

.panel-yellow a:hover {
color: #df8a13;
}

.Differences {
width: 100%;
border-collapse: collapse;
border-spacing: 0;
empty-cells: show;
}

.Differences thead th {
text-align: left;
border-bottom: 1px solid #000;
background: #aaa;
color: #000;
padding: 4px;
}
.Differences tbody th {
text-align: right;
background: #ccc;
width: 4em;
padding: 1px 2px;
border-right: 1px solid #000;
vertical-align: top;
font-size: 13px;
}

.Differences td {
padding: 1px 2px;
font-family: Consolas, monospace;
font-size: 13px;
}

.DifferencesSideBySide .ChangeInsert td.Left {
background: #dfd;
}

.DifferencesSideBySide .ChangeInsert td.Right {
background: #cfc;
}

.DifferencesSideBySide .ChangeDelete td.Left {
background: #f88;
}

.DifferencesSideBySide .ChangeDelete td.Right {
background: #faa;
}

.DifferencesSideBySide .ChangeReplace .Left {
background: #fe9;
}

.DifferencesSideBySide .ChangeReplace .Right {
background: #fd8;
}

.Differences ins, .Differences del {
text-decoration: none;
}

.DifferencesSideBySide .ChangeReplace ins, .DifferencesSideBySide .ChangeReplace del {
background: #fc0;
}

.Differences .Skipped {
background: #f7f7f7;
}

.DifferencesInline .ChangeReplace .Left,
.DifferencesInline .ChangeDelete .Left {
background: #fdd;
}

.DifferencesInline .ChangeReplace .Right,
.DifferencesInline .ChangeInsert .Right {
background: #dfd;
}

.DifferencesInline .ChangeReplace ins {
background: #9e9;
}

.DifferencesInline .ChangeReplace del {
background: #e99;
}
@endpush