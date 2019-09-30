<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Location;
use Illuminate\Support\Facades\Input;


use App\Http\Requests;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::get();
        return view('job.index',compact('jobs'));
    }

    public function showall(){
        $jobs = Job::with('location')->get();

        foreach ($jobs as $job){
            if ($job->fiz_live == "1"){
                $live = "yes";
            }else{
                $live = "no";
            }
            $array[] = [
                        "<a href='job/$job->id'>$job->id</a>",
                        $job->date,
                        $job->location->name,
                        $job->technician,
                        $job->time_spent,
                        $job->km,
                        $job->fault_description,
                        $job->resolution,
                        $live,
                        $job->signal,
                        $job->pi_down,
                        $job->pi_up,
                        $job->mweb_down,
                        $job->mweb_up,
                        "<a href='job/$job->id/edit'>Edit</a>"

            ];
        }
        return $array;


    }


    public function show($id){
        $job = Job::find($id);
        return view('job.show',compact('job'));
    }

    public function create(){
        $locations = Location::orderby('name','asc')->where('site_type','fiz')->lists('name','id');
        return view('job.create',compact('locations','users'));
    }

    public function edit($id){
        $job = Job::Find($id);
        switch ($job->technician) {
            case "CorrieE":
                $current_technician = 0;
                break;
            case "EstevanD":
                $current_technician = 1;
                break;

            case "PieterG":
                $current_technician = 2;
                break;

            case "LucasC":
                $current_technician = 3;
                break;
            default:

        }
        $locations = Location::orderby('name','asc')->where('site_type','fiz')->lists('name','id');

        switch ($job->reg_nr) {
            case "DK 61 YT GP":
                $current_reg = "0";
                break;
            case "DJ 96 TY GP":
                $current_reg = "1";
                break;

            case "DJ 92 VG GP":
                $current_reg = "2";
                break;

            case "DJ 96 TZ GP":
                $current_reg = "3";
                break;

            case "FJ 96 TY GP":
                $current_reg = "4";
                break;

            case "CA W4 91 04":
                $current_reg = "5";
                break;

            case "CA W4 47 81":
                $current_reg = "6";
                break;

            case "FJ 96 RY GP":
                $current_reg = "7";
                break;

            default:

        }

        switch ($job->fault_description) {
            case "Breakdown":
                $current_fault = "0";
                break;
            case "Maintenance":
                $current_fault = "1";

                break;

            case "Relocation":
                $current_fault = "2";

                break;

            case "New Install":
                $current_fault = "3";

                break;
            case "Preventative Maintenance":
                $current_fault = "4";

                break;
            case "Support Mail":
                $current_fault = "5";

                break;

            default:
        }

        return view('job.edit',compact('job','current_technician','current_reg','current_fault','locations'));
    }

    public function store(Request $request)
    {
        $input = Input::all();
        if ((array_key_exists('end_km',$input)) AND (array_key_exists('start_km',$input))){
            if((isset($input['end_km'])) and (isset($input['start_km'])) ) {
                if (($input['start_km']) < ($input['end_km'])) {
                    $job = Job::create($input);

                    switch ($job->technician) {
                        case "0":
                            $job->technician = "CorrieE";
                            break;
                        case "1":
                            $job->technician = "EstevanD";
                            break;

                        case "2":
                            $job->technician = "PieterG";
                            break;

                        case "3":
                            $job->technician = "LucasC";
                            break;


                        default:

                    }

                    switch ($job->reg_nr) {
                        case "0":
                            $job->reg_nr = "DK 61 YT GP";
                            break;
                        case "1":
                            $job->reg_nr = "DJ 96 TY GP";
                            break;

                        case "2":
                            $job->reg_nr = "DJ 92 VG GP";
                            break;

                        case "3":
                            $job->reg_nr = "DJ 96 TZ GP";
                            break;

                        case "4":
                            $job->reg_nr = "FJ 96 TY GP";
                            break;

                        case "5":
                            $job->reg_nr = "CA W4 91 04";
                            break;

                        case "6":
                            $job->reg_nr = "CA W4 47 81";
                            break;
                        case "7":
                            $job->reg_nr = "FJ 96 RY GP";
                            break;

                        default:

                    }

                    switch ($job->fault_description) {
                        case "0":
                            $job->fault_description = "Breakdown";
                            break;
                        case "1":
                            $job->fault_description = "Maintenance";
                            break;

                        case "2":
                            $job->fault_description = "Relocation";
                            break;

                        case "3":
                            $job->fault_description = "New Install";
                            break;
                        case "4":
                            $job->fault_description = "Preventative Maintenance";
                            break;
                        case "5":
                            $job->fault_description = "Support Mail";
                            break;

                        default:
                    }

                    $job->km = $input['end_km'] - $input['start_km'];
                    $job->save();
                }else{
                    $job = Job::create($input);

                    switch ($job->technician) {
                        case "0":
                            $job->technician = "CorrieE";
                            break;
                        case "1":
                            $job->technician = "EstevanD";
                            break;

                        case "2":
                            $job->technician = "PieterG";
                            break;

                        case "3":
                            $job->technician = "LucasC";
                            break;

                        default:

                    }

                    switch ($job->reg_nr) {
                        case "0":
                            $job->reg_nr = "DK 61 YT GP";
                            break;
                        case "1":
                            $job->reg_nr = "DJ 96 TY GP";
                            break;

                        case "2":
                            $job->reg_nr = "DJ 92 VG GP";
                            break;

                        case "3":
                            $job->reg_nr = "DJ 96 TZ GP";
                            break;

                        case "4":
                            $job->reg_nr = "FJ 96 TY GP";
                            break;

                        case "5":
                            $job->reg_nr = "CA W4 91 04";
                            break;

                        case "6":
                            $job->reg_nr = "CA W4 47 81";
                            break;
                        case "7":
                            $job->reg_nr = "FJ 96 RY GP";
                            break;

                        default:

                    }
                    switch ($job->fault_description) {
                        case "0":
                            $job->fault_description = "Breakdown";
                            break;
                        case "1":
                            $job->fault_description = "Maintenance";
                            break;

                        case "2":
                            $job->fault_description = "Relocation";
                            break;

                        case "3":
                            $job->fault_description = "New Install";
                            break;
                        case "4":
                            $job->fault_description = "Preventative Maintenance";
                            break;

                        default:
                    }

                    $job->km = 0;
                    $job->save();
                }
            }
        }

        return redirect("job");

    }

    public function update($id)
    {
        $input = Input::all();
        if ((array_key_exists('end_km',$input)) AND (array_key_exists('start_km',$input))) {
            if ((isset($input['end_km'])) and (isset($input['start_km']))) {
                if (($input['start_km']) < ($input['end_km'])) {

                    $job = Job::find($id)->update($input);
                    $job = Job::find($id);

                    switch ($job->technician) {
                        case "0":
                            $job->technician = "CorrieE";
                            break;
                        case "1":
                            $job->technician = "EstevanD";
                            break;

                        case "2":
                            $job->technician = "PieterG";
                            break;

                        case "3":
                            $job->technician = "LucasC";
                            break;


                        default:

                    }

                    switch ($job->reg_nr) {
                        case "0":
                            $job->reg_nr = "DK 61 YT GP";
                            break;
                        case "1":
                            $job->reg_nr = "DJ 96 TY GP";
                            break;

                        case "2":
                            $job->reg_nr = "DJ 92 VG GP";
                            break;

                        case "3":
                            $job->reg_nr = "DJ 96 TZ GP";
                            break;

                        case "4":
                            $job->reg_nr = "FJ 96 TY GP";
                            break;

                        case "5":
                            $job->reg_nr = "CA W4 91 04";
                            break;

                        case "6":
                            $job->reg_nr = "CA W4 47 81";
                            break;
                        case "7":
                            $job->reg_nr = "FJ 96 RY GP";
                            break;

                        default:

                    }

                    switch ($job->fault_description) {
                        case "0":
                            $job->fault_description = "Breakdown";
                            break;
                        case "1":
                            $job->fault_description = "Maintenance";
                            break;

                        case "2":
                            $job->fault_description = "Relocation";
                            break;

                        case "3":
                            $job->fault_description = "New Install";
                            break;
                        case "4":
                            $job->fault_description = "Preventative Maintenance";
                            break;
                        case "5":
                            $job->fault_description = "Support Mail";
                            break;

                        default:
                    }
                    $job->km = $input['end_km'] - $input['start_km'];
                    $job->save();
                } else {
                    $job = Job::find($id)->update($input);

                    switch ($job->technician) {
                        case "0":
                            $job->technician = "CorrieE";
                            break;
                        case "1":
                            $job->technician = "EstevanD";
                            break;

                        case "2":
                            $job->technician = "PieterG";
                            break;

                        case "3":
                            $job->technician = "LucasC";
                            break;

                        default:

                    }

                    switch ($job->reg_nr) {
                        case "0":
                            $job->reg_nr = "DK 61 YT GP";
                            break;
                        case "1":
                            $job->reg_nr = "DJ 96 TY GP";
                            break;

                        case "2":
                            $job->reg_nr = "DJ 92 VG GP";
                            break;

                        case "3":
                            $job->reg_nr = "DJ 96 TZ GP";
                            break;

                        case "4":
                            $job->reg_nr = "FJ 96 TY GP";
                            break;

                        case "5":
                            $job->reg_nr = "CA W4 91 04";
                            break;

                        case "6":
                            $job->reg_nr = "CA W4 47 81";
                            break;
                        case "7":
                            $job->reg_nr = "FJ 96 RY GP";
                            break;

                        default:

                    }

                    switch ($job->fault_description) {
                        case "0":
                            $job->fault_description = "Breakdown";
                            break;
                        case "1":
                            $job->fault_description = "Maintenance";
                            break;

                        case "2":
                            $job->fault_description = "Relocation";
                            break;

                        case "3":
                            $job->fault_description = "New Install";
                            break;
                        case "4":
                            $job->fault_description = "Preventative Maintenance";
                            break;

                        default:
                    }

                    $job->km = 0;
                    $job->save();
                }
            }


            $job->save();

            return redirect("job");

        }

    }
}
