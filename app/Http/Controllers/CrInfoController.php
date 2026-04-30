<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Imports\StudentImport;
use App\Models\Course;
use App\Models\CrInfo;
use App\Models\Day;
use App\Models\Holiday;
use App\Models\Loggins;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeacherAttendance;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Validators\ValidationException;




class CrInfoController extends Controller
{
    public function index(){

        $courses = Course::all();
        $semesters = Semester::all();
        $teacher = Auth::user();
        $crs = CrInfo::with(['course','semester'])->where("branch_id",$teacher->branch_id)->get();

        return view("regcr",compact("courses","semesters","crs"));

        }

    public function update(Request $request,$id)
        {

        $cr = CrInfo::findOrFail($id);

        $cr->update([
        'firstname'=>$request->firstname,
        'middlename'=>$request->middlename,
        'lastname'=>$request->lastname,
        'mobile'=>$request->mobile,
        'email'=>$request->email,
        'course_id'=>$request->course_id,
        'nta'=>$request->nta
        ]);

        return back()->with('success','Updated Successfully');

        }
  

    public function store(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'mobile' => 'required',
            'email' => 'required|email|unique:cr_info,email',
            'password' => 'required',
            'course_id' => 'required',
            'nta' => 'required'
        ]);

        CrInfo::create([
            "firstname" => $request->firstname,
            "middlename" => $request->middlename,
            "lastname" => $request->lastname,
            "mobile" => $request->mobile,
            "email" => $request->email,
            "password" => $request->password,
            "course_id" => $request->course_id,
            "nta" => $request->nta,
            "branch_id" => $branchId,
        ]);

        return redirect()->route('cr_info.index')
                         ->with('success', 'CR Registered Successfully');
    }
    public function lessons()
{
    $todayName = Carbon::now()->format('l');
    $todayDate = Carbon::today();
    DB::table('teacher_attendances')
        ->where('status', 'emergency')
        ->whereNotNull('date')
        ->where('date', '<=', now()->subDays(5))
        ->update([
            'status' => 'absent'
        ]);

    $user = Auth::guard('cr')->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Tafadhali login kwanza');
    }

    
    $holiday = Holiday::where('date', $todayDate)->first();

    if ($holiday) {
        return view('lessons', [
            'lessons' => [],
            'holidayMessage' => "Leo ni holiday: {$holiday->name}"
        ]);
    }


    $timetables = DB::table('timetables')
        ->join('subjects','subjects.id','=','timetables.subject_id')
        ->join('days','days.id','=','timetables.day_id')
        ->where('timetables.branch_id', $user->branch_id)
        ->where('days.day_name', $todayName)
        ->where('subjects.course_id', $user->course_id)
        ->where('subjects.nta_level', $user->nta)
        ->where('subjects.semester_id', $user->semester_id) 

        ->select(
            'timetables.id as timetable_id',
            'timetables.teacher_id',
            'timetables.subject_id'
        )
        ->get();

    foreach ($timetables as $tt) {

        if(!$tt->teacher_id){
            continue;
        }

        TeacherAttendance::firstOrCreate(
            [
                'timetable_id' => $tt->timetable_id,
                'date' => $todayDate,
            ],
            [
                'teacher_id' => $tt->teacher_id,
                'subject_id' => $tt->subject_id,
                'branch_id' => $user->branch_id,
                'status' => 'absent'
            ]
        );
    }
    $lessons = DB::table('teacher_attendances')
        ->join('timetables','timetables.id','=','teacher_attendances.timetable_id')
        ->join('subjects','subjects.id','=','timetables.subject_id')
        ->join('timeslots','timeslots.id','=','timetables.timeslot_id')
        ->join('days','days.id','=','timetables.day_id')
        ->leftJoin('rooms','rooms.id','=','timetables.room_id')
        ->leftJoin('teachers','teachers.id','=','timetables.teacher_id')

        ->where('teacher_attendances.date', $todayDate)
        ->where('subjects.course_id', $user->course_id)
        ->where('subjects.nta_level', $user->nta)
        ->where('subjects.semester_id', $user->semester_id) 

        ->orderBy('timeslots.start_time')

        ->select(
            'timetables.id as timetable_id',
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.subjectCode',
            'rooms.name as room_name',
            'teacher_attendances.status',
            'teachers.firstname',
            'teachers.lastname'
        )
        ->get();

    $emergencyLessons = DB::table('teacher_attendances')
    ->join('subjects', 'teacher_attendances.subject_id', '=', 'subjects.id')
    ->join('teachers', 'teacher_attendances.teacher_id', '=', 'teachers.id')

    
    ->join('timetables', 'teacher_attendances.timetable_id', '=', 'timetables.id')

    
    ->leftJoin('rooms', 'timetables.room_id', '=', 'rooms.id')

    ->where('teacher_attendances.status', 'emergency')

    ->where('subjects.course_id', $user->course_id)
    ->where('subjects.nta_level', $user->nta)
    ->where("subjects.branch_id",$user->branch_id)
    ->where('subjects.semester_id', $user->semester_id)

    ->select(
        'teacher_attendances.*',
        'subjects.subjectName',
        'subjects.subjectCode',
        'subjects.nta_level',
        'teachers.firstname',
        'teachers.lastname',
        'rooms.name as room_name'
    )
    ->get();

    return view("lessons", compact("lessons","emergencyLessons"));
}
    public function store1(Request $request)
{
    
    $attendance = TeacherAttendance::where('timetable_id', $request->timetable_id)
                    ->where('date', Carbon::today())
                    ->firstOrFail();

    $attendance->update([
        'status' => 'present'
    ]);

    return back()->with('success','Attendance marked Present');
}
    public function studenttbl()
{

    $student = Auth::guard('cr')->user();

    $course_id = $student->course_id;
    $nta_level = $student->nta;
    $semester_id = $student->semester_id;

    $timetable = DB::table('timetables')

        ->join('subjects','subjects.id','=','timetables.subject_id')
        ->join('courses','courses.id','=','subjects.course_id')
        ->join('semesters','semesters.id','=','subjects.semester_id')
        ->join('timeslots','timeslots.id','=','timetables.timeslot_id')
        ->leftJoin('rooms','rooms.id','=','timetables.room_id')
        ->join('days','days.id','=','timetables.day_id')

        // muhimu sana
        ->leftJoin('teachers','teachers.id','=','timetables.teacher_id')

        ->where('subjects.course_id',$course_id)
        ->where("subjects.branch_id",$student->branch_id)
        ->where('subjects.nta_level',$nta_level)
        ->where('subjects.semester_id',$semester_id)

        ->orderByRaw("
        FIELD(days.day_name,
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday')
        ")

        ->orderBy('timeslots.start_time')

        ->select(
            'days.day_name as day',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.subjectCode',
            'semesters.semname as semester',
            'courses.courseName',
            'subjects.nta_level',
            'timetables.group_name',
            'rooms.name as room_name',
            'teachers.firstname',
            'teachers.middlename',
            'teachers.lastname'
        )

        ->get();


    $timeslots = $timetable
        ->map(fn($t)=>[
            'start'=>$t->start_time,
            'end'=>$t->end_time
        ])
        ->unique()
        ->sortBy('start')
        ->values();


    $entries = $timetable->groupBy(['semester','day']);

    return view('studenttbl',compact('entries','timeslots'));
}
    public function studentsub()
{
    $student = Auth::guard('cr')->user();

    $course_id = $student->course_id;
    $courseName = $student->course->courseName;
    $nta1 = $student->nta;
    $semester_id = $student->semester_id; 

    $subjects = Subject::with(['course','semester','teacher'])
        ->where('course_id', $course_id)
        ->where('nta_level', $nta1)
        ->where("branch_id",$student->branch_id)
        ->where('semester_id', $semester_id) 
        ->orderBy('semester_id')
        ->get();

    return view('studentSubject', compact('subjects','courseName','nta1'));
}


    public function template(){
            return Excel::download(new StudentExport,"student_format.xlsx");
        }
    public function StudentImport(Request $request)
{
    $request->validate([
        'student_file' => 'required|file|mimes:xlsx,csv',
    ], [
        'student_file.required' => 'Please upload a file',
        'student_file.mimes'    => 'Only Excel files (xlsx, csv) are allowed',
    ]);

    $file = $request->file('student_file');

    
    $expectedHeaders = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'mobile',
        'password',
        'course_name',
        'nta_level'
    ];

    // Soma Excel
    $spreadsheet = IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();

    $headerRow = $sheet->rangeToArray('A1:H1', NULL, TRUE, TRUE, TRUE)[1];

    // Safisha headers
    $uploadedHeaders = array_map(function ($header) {
        return strtolower(trim($header));
    }, array_values($headerRow));

    // Angalia kama kuna columns zinakosekana
    $missing = array_diff($expectedHeaders, $uploadedHeaders);

    if (!empty($missing)) {
        return back()->withErrors([
            'student_file' => 'Missing columns: ' . implode(', ', $missing)
        ]);
    }

    $import = new StudentImport(Auth::user()->branch_id);
    
    Excel::import($import, $file);


   

    // Log activity
    Loggins::create([
        'title' => 'New Registration',
        'action' => 'New Excel of Students is recorded',
    ]);

    return back()->with('success', 'Students imported successfully!');
}


}
