<?php

namespace App\Http\Controllers;

use App\Exports\SubjectExportTemplate;
use App\Imports\SubjectImport;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Semester;
use App\Models\Subject;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;

class SubjectController extends Controller
{
    public function index()
{
    $teachers = Teacher::where("user_level", "teacher")->get();
    $courses1 = Course::all();

    $semester = Semester::where("status", "Active")->get();
    $activeSemesters = $semester->pluck("id");

    $degreeCourses = Course::where('course_level', 'degree')
        ->with(['subjects' => function($q) use ($activeSemesters){
            $q->whereIn('semester_id', $activeSemesters)
              ->with(['teacher','semester'])
              ->orderBy('subjectCode','ASC');
        }])->get();

    $diplomaCourses = Course::where('course_level', 'diploma')
        ->with(['subjects' => function($q) use ($activeSemesters){
            $q->whereIn('semester_id', $activeSemesters)
              ->with(['teacher','semester'])
              ->orderBy('subjectCode','ASC');
        }])->get();

    return view("subject", compact(
        "teachers",
        "courses1",
        "degreeCourses",
        "diplomaCourses",
        "semester"
    ));
}


    public function store(Request $request){
            $request->validate([
                "subName" => "required",
                "subCode" => "required",
                "teacher" => "required",
                "course" => "required",
                "nta" => "required",
                "semester" => "required",
                "subject_type" => "required",
                "required_lab" => "required",
                "crhour" => "required"
            ]);
            $subIn = Subject::create([
                "course_id" =>$request->course,
                "subjectName" =>$request->subName,
                "subjectCode" =>$request->subCode,
                "teacher_id" =>$request->teacher,
                "nta_level" =>$request->nta,
                "semester_id" =>$request->semester,
                "subject_type" =>$request->subject_type,
                "required_lab" =>$request->required_lab,
                "credit_hour" => $request->crhour
            ]);
            if($subIn){
                return redirect()->back()->with("success","New subject is Assigned Successfully");
            }

        }
        public function edit($id){
            $teacher = Teacher::where("user_level","teacher")->get();
            $subject = Subject::findOrFail($id);
            $course = Course::all();
            return view("subjectEdit",compact("teacher","subject","course"));
        }
        public function update(Request $request,$id){
            $request->validate([
                "subjectName" => "required",
                "subjectCode" => "required",
                "teacher_id" => "required",
                "course_id" => "required",
                "nta_level" => "required",
                "subject_type" => "required",
                "required_lab" => "required",
                "credit_hour" => "required"
            ]);
            $subject = Subject::findOrFail($id);
            $subject->update($request->all());
            return redirect()->route("subject.index")->with("success","Subject is updated sucess");
        }
        public function import(Request $request)
{
    $request->validate([
        'subject_file' => 'required|file|mimes:xlsx,csv',
    ], [
        'subject_file.required' => 'Please upload a file',
        'subject_file.mimes'    => 'Only Excel files (xlsx, csv) are allowed',
    ]);

    $file = $request->file('subject_file');

    // 1️⃣ Check headers
    $expectedHeaders = ['subjectname','subjectcode','course_code','nta_level','sem_code','teacher_code','subject_type','required_lab','credit_hour'];

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();
    $headerRow = $sheet->rangeToArray('A1:I1', NULL, TRUE, TRUE, TRUE)[1];

    $uploadedHeaders = array_map('strtolower', array_values($headerRow));

    if ($uploadedHeaders !== $expectedHeaders) {
        return back()->withErrors(['subject_file' => 'File is not match']);
    }

    $import = new SubjectImport;

    try {
        Excel::import($import, $file);
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errors = [];
        foreach ($failures as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    } catch (\Exception $e) {
        return back()->withErrors(['subject_file' => 'File is not match']);
    }

    if ($import->failures()->isNotEmpty()) {
        $errors = [];
        foreach ($import->failures() as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    }

    return back()->with("success","Excel data is inserted successfully");
}
        public function subjectTemplate(){
            return Excel::download(new SubjectExportTemplate,"subject_entry_format.xlsx");

        }
        public function destroy($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return redirect()->route('subject.index')->with('error', 'Subject not found.');
        }

        $subject->delete();

        return redirect()->route('subject.index')->with('success', 'Subject deleted successfully.');
    }
}
