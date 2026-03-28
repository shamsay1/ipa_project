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

        $crs = CrInfo::with(['course','semester'])->get();

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

        CrInfo::create($request->all());

        return redirect()->route('cr_info.index')
                         ->with('success', 'CR Registered Successfully');
    }
    public function lessons()
{
    $todayName = Carbon::now()->format('l');
    $todayDate = Carbon::today();

    $user = Auth::guard('cr')->user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Tafadhali login kwanza');
    }

    // ===== CHECK HOLIDAY =====
    $holiday = Holiday::where('date', $todayDate)
                ->where('status', 'Paused')
                ->first();

    // kama ni paused day usiingize attendance
    if (!$holiday) {

        // pata day_id
        $day = Day::where('day_name', $todayName)->first();

        if (!$day) {
            return back()->with('error','Day not found');
        }

        // pata ratiba za leo
        $timetables = Timetable::with(['teacher','subject','course'])
            ->where('day_id', $day->id)
            ->whereHas('subject', function($q) use ($user) {
                $q->where('course_id', $user->course_id)
                  ->where('nta_level', $user->nta);
            })
            ->get();

        // ===== INGIZA AUTOMATIC ATTENDANCE =====
        foreach ($timetables as $tt) {

            // hakikisha teacher_id ipo
            if(!$tt->teacher_id){
                continue;
            }

            TeacherAttendance::firstOrCreate(
                [
                    'timetable_id' => $tt->id,
                    'date' => $todayDate,
                ],
                [
                    'teacher_id' => $tt->teacher_id,
                    'subject_id' => $tt->subject_id,
                    'status' => 'absent'
                ]
            );
        }
    }

    // ===== LETA ATTENDANCE YA LEO =====
    $lessons = TeacherAttendance::with(['subject','timetable.timeslot','timetable.course'])
        ->where('date', $todayDate)
        ->whereHas('subject', function($q) use ($user){
            $q->where('course_id', $user->course_id)
              ->where('nta_level', $user->nta);
        })
        ->get();

    return view("lessons", compact("lessons"));
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

    // Columns zinazotakiwa
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

    $import = new StudentImport();
    Excel::import($import, $file);


   

    // Log activity
    Loggins::create([
        'title' => 'New Registration',
        'action' => 'New Excel of Students is recorded',
    ]);

    return back()->with('success', 'Students imported successfully!');
}


}
