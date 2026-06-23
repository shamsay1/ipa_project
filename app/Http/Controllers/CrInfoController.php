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
   public function index(Request $request)
{
    $courses = Course::all();
    $semesters = Semester::all();

    $teacher = Auth::user();

    $search = $request->search;

    $crs = CrInfo::with(['course', 'semester'])
        ->where('branch_id', $teacher->branch_id)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('middlename', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('nta', 'like', "%{$search}%")
                  ->orWhereHas('course', function ($course) use ($search) {
                      $course->where('courseName', 'like', "%{$search}%");
                  });
            });
        })
        ->paginate(10);

    return view('regcr', compact(
        'courses',
        'semesters',
        'crs'
    ));
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
        "semester_id" => $request->semester_id,
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
            'semester_id' => 'required',
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
            "semester_id" => $request->semester_id,
            "nta" => $request->nta,
            "branch_id" => $branchId,
        ]);

        return redirect()->route('cr_info.index')
                         ->with('success', 'CR Registered Successfully');
    }

    public function destroy($id)
{
    $student = CrInfo::findOrFail($id);

    $student->delete();

    return redirect()->back()
        ->with('success', 'Student deleted successfully.');
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
        return redirect()->route('login')
            ->with('error', 'Tafadhali login kwanza');
    }

    $holiday = Holiday::whereDate('date', $todayDate)->first();

    if ($holiday) {
        return view('lessons', [
            'lessons' => [],
            'emergencyLessons' => [],
            'holidayMessage' => "Leo ni holiday: {$holiday->name}"
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ratiba za leo kwa CR husika
    |--------------------------------------------------------------------------
    */
    $timetables = DB::table('timetables')
        ->join('subjects', 'subjects.id', '=', 'timetables.subject_id')
        ->join('days', 'days.id', '=', 'timetables.day_id')

        ->where('days.day_name', $todayName)
        ->where('subjects.course_id', $user->course_id)
        ->where('subjects.nta_level', $user->nta)
        ->where('subjects.semester_id', $user->semester_id)
        ->where('subjects.branch_id', $user->branch_id)

        ->select(
            'timetables.id as timetable_id',
            'subjects.teacher_id',
            'subjects.id as subject_id'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Tengeneza attendance records kama hazipo
    |--------------------------------------------------------------------------
    */
    foreach ($timetables as $tt) {

        if (empty($tt->teacher_id)) {
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

    /*
    |--------------------------------------------------------------------------
    | Vipindi vya leo
    |--------------------------------------------------------------------------
    */
    $lessons = DB::table('teacher_attendances')
        ->join('timetables', 'timetables.id', '=', 'teacher_attendances.timetable_id')
        ->join('subjects', 'subjects.id', '=', 'timetables.subject_id')
        ->join('timeslots', 'timeslots.id', '=', 'timetables.timeslot_id')
        ->join('days', 'days.id', '=', 'timetables.day_id')
        ->leftJoin('rooms', 'rooms.id', '=', 'timetables.room_id')
        ->leftJoin('teachers', 'teachers.id', '=', 'teacher_attendances.teacher_id')

        ->whereDate('teacher_attendances.date', $todayDate)

        ->where('subjects.course_id', $user->course_id)
        ->where('subjects.nta_level', $user->nta)
        ->where('subjects.semester_id', $user->semester_id)
        ->where('subjects.branch_id', $user->branch_id)

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
            'teachers.middlename',
            'teachers.lastname'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Emergency lessons
    |--------------------------------------------------------------------------
    */
    $emergencyLessons = DB::table('teacher_attendances')
        ->join('subjects', 'teacher_attendances.subject_id', '=', 'subjects.id')
        ->join('teachers', 'teacher_attendances.teacher_id', '=', 'teachers.id')
        ->join('timetables', 'teacher_attendances.timetable_id', '=', 'timetables.id')
        ->leftJoin('rooms', 'timetables.room_id', '=', 'rooms.id')

        ->where('teacher_attendances.status', 'emergency')

        ->where('subjects.course_id', $user->course_id)
        ->where('subjects.nta_level', $user->nta)
        ->where('subjects.semester_id', $user->semester_id)
        ->where('subjects.branch_id', $user->branch_id)

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

    return view('lessons', compact(
        'lessons',
        'emergencyLessons'
    ));
}
//     public function store1(Request $request)
// {
    
//     $attendance = TeacherAttendance::where('timetable_id', $request->timetable_id)
//                     ->where('date', Carbon::today())
//                     ->firstOrFail();

//     $attendance->update([
//         'status' => 'present'
//     ]);

//     return back()->with('success','Attendance marked Present');
// }

    public function store1(Request $request)
{
    $attendance = TeacherAttendance::where('timetable_id', $request->timetable_id)
        ->where('status', 'emergency')
        ->latest('date')
        ->first();

    if (!$attendance) {

        $attendance = TeacherAttendance::where('timetable_id', $request->timetable_id)
            ->whereDate('date', Carbon::today())
            ->firstOrFail();
    }

    $attendance->update([
        'status' => 'present'
    ]);

    return back()->with(
        'success',
        'Attendance marked Present successfully'
    );
}
    public function studentTimetable()
{
    $student = Auth::guard('cr')->user();
    

    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->orderBy('id')
        ->get();

    if ($activeSemesters->isEmpty()) {
        return redirect()->back()->with('error', 'No active semesters found');
    }

    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')

        ->where('subjects.course_id', $student->course_id)
        ->where('subjects.nta_level', $student->nta)
        ->where('subjects.semester_id', $student->semester_id)

        ->select(
            'timetables.id as timetable_id',
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.nta_level',
            'subjects.credit_hour',
            'subjects.group_name',
            'courses.courseName',
            'courses.short_name',
            'teachers.firstname',
            'teachers.middlename',
            'teachers.lastname',
            'rooms.name as room_name',
            'semesters.semName as semester_name'
        )
        ->orderBy('semesters.id')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get();

    $timetableData = [];

    $semesterGroups = $entries->groupBy('semester_name');

    foreach ($semesterGroups as $semester => $semesterEntries) {

        $ntaGroups = $semesterEntries->groupBy('nta_level');

        foreach ($ntaGroups as $ntaLevel => $ntaEntries) {

            $item = $ntaEntries->first();

            switch ($item->nta_level) {
                case "NTA-4":
                    $prefix = 'BTC';
                    break;
                case "NTA-5":
                    $prefix = 'TC';
                    break;
                case "NTA-6":
                    $prefix = 'OD';
                    break;
                case "NTA-7":
                    $prefix = 'HD';
                    break;
                case "NTA-8":
                    $prefix = 'B';
                    break;
                default:
                    $prefix = '';
            }

            if (str_contains($semester, '1')) {
                $semesterRoman = 'I';
            } elseif (str_contains($semester, '2')) {
                $semesterRoman = 'II';
            } elseif (str_contains($semester, '3')) {
                $semesterRoman = 'III';
            } elseif (str_contains($semester, '4')) {
                $semesterRoman = 'IV';
            } else {
                $semesterRoman = '';
            }
            

            $timetableData[] = [
                'semester' => $semester,
                'course' => $prefix . $item->short_name . ' ' . $semesterRoman,
                'course1' => $item->courseName,
                'nta_level' => $ntaLevel,
                'entries' => $ntaEntries->groupBy('day_name')
            ];
        }
    }

    return view('studenttbl', compact('timetableData'));
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
