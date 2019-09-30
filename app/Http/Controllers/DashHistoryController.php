<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Device;
use App\Location;
use App\DashHistory;
Use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\Redirect;
use App\DeviceUpdateController;
use App\Devicetype;
use Khill\Lavacharts\Lavacharts;


class DashHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dashhistories = DashHistory::orderby('created_at','desc')->take(180)->get();
//        $dashhistory->down_devices = DashHistory::getDownDevicesCount();
//        $dashhistory->problem_devices = DashHistory::getProblemDevices();
//        $dashhistory->power_monitors_down = DashHistory::getDownPowerMons();
        $totalpppoe = $this->getTotalPPPoeChart($dashhistories);
        $downdevices = $this->getDownDevicesChart($dashhistories);
        $problemdevices = $this->getProblemDevicesChart($dashhistories);
        $downpowermonitors = $this->getPowerMonitorsDownChart($dashhistories);
       // $sysload = $this->getSystemloadChart();
        return view('dashhistory.index',compact('totalpppoe','downdevices','problemdevices','downpowermonitors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashhistory.create',compact('devicetypes','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        //dd($input);
        DashHistory::create($input);
        $dashhistorys= DashHistory::get();

        return view('dashhistory.index',compact('dashhistorys'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dashhistory = DashHistory::find($id);

        return view('dashhistory.show',compact('dashhistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function updateDashHistory($id){
        DashHistory::updateDashHistory($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTotalPPPoeChart($dashhistories)
    {
        $totalpppoe = \Lava::DataTable();
        $totalpppoe->addDateColumn('Date')
            ->addNumberColumn('Active PPPoe');
        foreach ($dashhistories as $dashhistory) {
            $totalpppoe->addRow([$dashhistory['created_at'], $dashhistory['active_pppoe'] ]);
        }

        $pppoelevels = array("0","500","1000","1500","2000","2500","3000","3500","4000","4500","5000");
        \Lava::AreaChart('totalpppoe', $totalpppoe, [
            'title' => 'Active PPPoe',

            'vAxis' => [$pppoelevels],
            'legend' => [
                'position' => 'in'
            ]
        ]);
        return $totalpppoe;
    }


    public function getDownDevicesChart($dashhistories)
    {
        $downdevices = \Lava::DataTable();
        $downdevices->addDateColumn('Date')
            ->addNumberColumn('Down Devices');
        foreach ($dashhistories as $dashhistory) {
            $downdevices->addRow([$dashhistory['created_at'], $dashhistory['down_devices']]);
        }

        \Lava::AreaChart('downdevices', $downdevices, [
            'title' => 'Down Devices',
            'legend' => [
                'position' => 'in'
            ]
        ]);
        return $downdevices;
    }

    public function getProblemDevicesChart($dashhistories)
    {
        $problemdevices = \Lava::DataTable();
        $problemdevices->addDateColumn('Date')
            ->addNumberColumn('Problem Devices');
        foreach ($dashhistories as $dashhistory) {
            $problemdevices->addRow([$dashhistory['created_at'], $dashhistory['problem_devices']]);
        }

        \Lava::AreaChart('problemdevices', $problemdevices, [
            'title' => 'Problem Devices',
            'legend' => [
                'position' => 'in'
            ]
        ]);
        return $problemdevices;
    }

    public function getPowerMonitorsDownChart($dashhistories)
    {
        $downpowermonitors = \Lava::DataTable();
        $downpowermonitors->addDateColumn('Date')
            ->addNumberColumn('Down Power Monitors');
        foreach ($dashhistories as $dashhistory) {
            $downpowermonitors->addRow([$dashhistory['created_at'], $dashhistory['power_monitors_down']]);
        }

        \Lava::AreaChart('downpowermonitors', $downpowermonitors, [
            'title' => 'Down Power Monitors',
            'legend' => [
                'position' => 'in'
            ]
        ]);
        return $downpowermonitors;
    }

    public function getSystemloadChart()
    {

        $sysload = \Lava::DataTable();
        $sysload->addDateColumn('Date')
            ->addNumberColumn('System Load');
        $rrdFile ="/var/www/html/dte/rrd/system/load.rrd";
        $result = \rrd_fetch( $rrdFile, array( config('rrd.ds'), "--resolution" , config("rrd.step"), "--start", (time()-20000), "--end", (time()-350) ) );
        dd($result);

        $pppoelevels = array("0","500","1000","1500","2000","2500","3000","3500","4000","4500","5000");
        \Lava::AreaChart('totalpppoe', $totalpppoe, [
            'title' => 'Active PPPoe',

            'vAxis' => [$pppoelevels],
            'legend' => [
                'position' => 'in'
            ]
        ]);
        return $totalpppoe;
    }
}
