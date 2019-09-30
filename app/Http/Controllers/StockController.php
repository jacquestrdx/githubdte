<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Stock;
use App\Http\Requests;
use App\Statable;

class StockController extends Controller
{
    public function index(){
    }


    public function show($id){

    }

    public function create($id){
        return view('stock.create',compact('id','users'));
    }

    public function edit($id){
        $stock = Stock::Find($id);
        return view('stock.edit',compact('stock'));
    }

    public function store(Request $request)
    {
        $input = Input::all();
        $stock = Stock::create($input);
        switch ($stock->description) {
            case "0":
                $stock->description = "Tough Switch";
                break;
            case "1":
                $stock->description = "Power Beam";
                break;
            case "2":
                $stock->description = "Rocket";
                break;
            case "3":
                $stock->description = "48V POE";
                break;
            case "4":
                $stock->description = "24V POE";
                break;
            case "5":
                $stock->description = "0.5M Flylead";
                break;
            case "6":
                $stock->description = "1M Flylead";
                break;
            case "7":
                $stock->description = "5M Flylead";
                break;
            case "8":
                $stock->description = "Cat 5e (m)";
                break;
            case "9":
                $stock->description = "Power Cable (m)";
                break;
            case "10":
                $stock->description = "Surge Arrestor";
                break;
            case "11":
                $stock->description = "Offset Bracket";
                break;
            case "12":
                $stock->description = "Steel Pole (m)";
                break;
            case "13":
                $stock->description = "Mikrotik 951";
                break;
            case "14":
                $stock->description = "Ruckus T300";
                break;
            case "15":
                $stock->description = "Ruckus T301";
                break;
            case "16":
                $stock->description = "Nano Station M5";
                break;
            case "17":
                $stock->description = "Nano Station M2 ";
                break;
            default:

        }
        $stock->save();
        $job = Job::find($stock->job_id);
        return redirect("job/$job->id");

    }

    public function update($id)
    {
        $input = Input::all();
        Stock::find($id)->update($input);
        $stock = Stock::find($id);
        $job = Job::find($stock->job_id);
        return redirect("job/$job->id");
    }
    
}
