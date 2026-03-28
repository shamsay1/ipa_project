<?php

namespace App\Http\Controllers;

use App\Exports\CourseExportTemplate;
use App\Imports\CourseImport;
use App\Models\Building;
use App\Models\Department;
use App\Models\Course;
use App\Models\Loggins;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CourseController extends Controller
{
    public function index()
    {
        $depts = Department::all();

        $deptCourses = Department::with(['courses' => function ($q) {
            $q->orderBy('courseName');
        }])->get();
        foreach ($deptCourses as $dept) {
            $dept->degree_courses  = $dept->courses->where('course_level', 'Degree')->values();
            $dept->diploma_courses = $dept->courses->where('course_level', 'Diploma')->values();
        }

        $bui = Building::all();

        return view("course", compact("depts", "deptCourses", "bui"));
    }

    public function export()
    {
        return Excel::download(new CourseExportTemplate, "course_entry_format.xlsx");
    }

    public function store(Request $request)
    {
        $request->validate([
            "courseName"     => "required",
            "deptId"         => "required",
            "courseCode"     => "required",
            "course_level"   => "required",
        ]);

        Course::create([
            "courseName"     => $request->courseName,
            "deptId"         => $request->deptId,
            "course_code"    => $request->courseCode,
            "course_level"   => $request->course_level
        ]);

        return redirect()->back()->with("success", "New course is registered");
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $dept   = Department::all();

        return view("courseEdit", compact("course", "dept"));
    }

     public function import(Request $request){
            Excel::import(new CourseImport,$request->file("course_file"));
            Loggins::create([
        'title' => 'New Courses Registration',
        'action' => 'New courses are registered',
    ]);
            return back()->with("success","Excel data is uploaded success");
        }

    public function update(Request $request, $id)
    {
        $request->validate([
            "courseName"     => "required",
            "course_code"    => "required",
            "deptId"         => "required",
            "course_level"   => "required"
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'courseName'     => $request->courseName,
            'course_code'    => $request->course_code,
            'deptId'         => $request->deptId,
            'course_level'   => $request->course_level
        ]);

        return redirect()->route("course.index")->with("success", "Course updated successfully");
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('course.index')->with('error', 'Course not found.');
        }

        $course->delete();

        return redirect()->route('course.index')->with('success', 'Course deleted successfully.');
    }
}
