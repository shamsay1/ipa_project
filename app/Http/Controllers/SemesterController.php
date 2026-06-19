<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index(){
            $sems = Semester::all();
            return view("semester",compact("sems"));
        }
    public function store(Request $request){
            $request->validate([
                "semName" => "required",
                "start_date" => "required",
                "end_date" => "required",
                "ac_year" => "required",
                "semCode",
            ]);
            $semIn = Semester::create([
                "semName" => $request->semName,
                "academic_year" =>$request->ac_year,
                "start_date" => $request->start_date,
                "end_date" =>$request->end_date,
                "semCode" => $request->semCode,
            ]);
            if($semIn){
                return redirect()->back()->with("success","Semester is Added successfully");
            }
        }
        public function changeStatus($id, $status)
{
    $semester = Semester::findOrFail($id);

    if ($status === "Active") {
        
        $chageSem = Semester::where('id', '=', $id);
        $chageSem->update(['status' => 'Inctive']);
    }
    else{
        $chageSem = Semester::where('id', '=', $id);
        $chageSem->update(['status' => 'Active']);

    }

    $semester->status = $status;
    $semester->save();

    return redirect()->route("semester.index")
                     ->with('success', 'Semester status updated successfully!');
}



    public function update(Request $request)
{
    $semester = Semester::findOrFail($request->id);

    $semester->update([
        'semName' => $request->semName,
        'academic_year' => $request->academic_year,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'semCode' => $request->semCode,
    ]);

    return redirect()->back()
        ->with('success', 'Semester updated successfully');
}


      

}
