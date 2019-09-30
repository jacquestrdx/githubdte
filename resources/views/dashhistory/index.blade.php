


@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div id="totalpppoe_div"></div>
                        </div>
                        <div class="row">
                            <div id="downdevices_div"></div>
                        </div>
                        <div class="row">
                            <div id="problemdevices_div"></div>
                        </div>
                        <div class="row">
                            <div id="downpowermonitors_div"></div>
                        </div>
                        <?= Lava::render('AreaChart', 'totalpppoe', 'totalpppoe_div') ?>
                        <?= Lava::render('AreaChart', 'problemdevices', 'problemdevices_div') ?>
                        <?= Lava::render('AreaChart', 'downdevices', 'downdevices_div') ?>
                        <?= Lava::render('AreaChart', 'downpowermonitors', 'downpowermonitors_div') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
