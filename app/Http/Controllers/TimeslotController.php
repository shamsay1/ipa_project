<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use Illuminate\Http\Request;

class TimeslotController extends Controller
{
    public function index(){
        $timeslot = Timeslot::orderBy("start_time","asc")->get();
        return view("timeslot",compact("timeslot"));
    }
    public function store(Request $request){
        $request->validate([
            "start_time" => "required",
            "end_time" => "required"
        ]);
        $timeslot = Timeslot::create($request->all());
        if($timeslot){
            return redirect()->route("timeslot.index")->with("success","Time Slot added success");
        }
    }
    public function edit($id){
        $time = Timeslot::findOrFail($id);
        return view("TimeslotEdit",compact("time"));
    }
    public function update(Request $request,$id){
        $request->validate([
            "start_time" => "required",
            "end_time" => "required",
        ]);
        $time= Timeslot::findOrFail($id);
        $time->update($request->all());
        return redirect()->route("timeslot.index")->with("success","Timeslot updated success");


    }
    public function destroy($id)
    {
        $timeslot = Timeslot::find($id);

        if (!$timeslot) {
            return redirect()->route('timeslot.index')->with('error', 'Timeslot not found.');
        }

        $timeslot->delete();

        return redirect()->route('timeslot.index')->with('success', 'Timeslot deleted successfully.');
    }
}
