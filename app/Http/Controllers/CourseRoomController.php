<?php

namespace App\Http\Controllers;

use App\Imports\CourseRoomsImport;
use App\Models\Course;
use App\Models\Course_room;
use App\Models\Room;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class CourseRoomController extends Controller
{
    public function index(){
        $teacher = Auth::user();
        $classrooms = Course_room::with("course", "room")->where("course_rooms.branch_id",$teacher->branch_id)->get();
        $rooms = Room::all();
        $courses = Course::all();
        return view("roomasign",compact("classrooms","courses","rooms"));
    }
   public function store(Request $request)
{
    
    $request->validate([
        'course_id'  => 'required|exists:courses,id',
        'nta_level'  => 'required|string',
        'group_name' => 'nullable|string',
        'total_students' => 'nullable|string',
        'room_id'    => 'required|exists:rooms,id',
    ]);

    
    // $roomUsedInSameNta = Course_room::where('room_id', $request->room_id)
    //     ->where('nta_level', $request->nta_level)
    //     ->where('group_name', '!=', $request->group_name)
    //     ->exists();

    // if ($roomUsedInSameNta) {
    //     return back()->withErrors([
    //         'room_id' => 'Chumba hiki tayari kimetumika na group nyingine ya NTA hii. Tafadhali chagua chumba kingine.',
    //     ])->withInput();
    // }

    
    $exists = Course_room::where('course_id', $request->course_id)
        ->where('nta_level', $request->nta_level)
        ->where('group_name', $request->group_name)
        ->where('room_id', $request->room_id)
        ->exists();

    // if ($exists) {
    //     return back()->withErrors([
    //         'room_id' => 'Taarifa hii tayari ipo. Tafadhali chagua chumba kingine.',
    //     ])->withInput();
    // }

    
    Course_room::create([
        'course_id'  => $request->course_id,
        'nta_level'  => $request->nta_level,
        'group_name' => $request->group_name,
        'total_students' =>$request->total_students,
        'room_id'    => $request->room_id,
    ]);

    return redirect()->back()->with('success', 'Course room inserted successfully');
}
    public function destroy($id)
    {
        
        $classroom = Course_room::find($id);

        if (!$classroom) {
            return redirect()->route('course-classrooms.index')
                ->with('error', 'Classroom not found.');
        }
        $classroom->delete();
        return redirect()->route('course-classrooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }
    public function importCourseRooms(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);
    
     Excel::import(new CourseRoomsImport(Auth::user()->branch_id), $request->file('file'));

    return back()->with('success', 'Course Rooms imported successfully');
}


}
