<?php

namespace App\Http\Controllers;

use App\Exports\TeacherTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;
use App\Models\Course;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\Loggins;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SystemTimetable;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Maatwebsite\Excel\Validators\ValidationException;



class TeacherController extends Controller
{

public function TeacherImport(Request $request)
{
   
    $request->validate([
        'teacher_file' => 'required|file|mimes:xlsx,csv',
    ], [
        'teacher_file.required' => 'Please upload a file',
        'teacher_file.mimes'    => 'Only Excel files (xlsx, csv) are allowed',
    ]);

    $file = $request->file('teacher_file');

    $expectedHeaders = ['firstname','middlename', 'lastname', 'email', 'mobile', 'gender', 'password','teacher_code', 'dept_code'];

    $spreadsheet = IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();
    $headerRow = $sheet->rangeToArray('A1:I1', NULL, TRUE, TRUE, TRUE)[1]; // A1:H1

    $uploadedHeaders = array_map('strtolower', array_values($headerRow));

    if ($uploadedHeaders !== $expectedHeaders) {
        return back()->withErrors(['teacher_file' => 'File is not match']);
    }

    $import = new TeachersImport(Auth::user()->branch_id);

    try {
        Excel::import($import, $file);
    } catch (ValidationException $e) {
        $failures = $e->failures();
        $errors = [];
        foreach ($failures as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    } catch (\Exception $e) {

        return back()->withErrors(['teacher_file' => 'File is not match']);
    }
    if ($import->failures()->isNotEmpty()) {
        $errors = [];
        foreach ($import->failures() as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    }
    Loggins::create([
        'title' => 'New Registration',
        'action' => 'New Excell of Teachers is recorded',
    ]);

    return back()->with('success', 'Teachers imported successfully!');
}


    public function index(Request $request)
{
    $teacher = Auth::user();
    $query = Teacher::where('branch_id', $teacher->branch_id)->where("user_level","teacher");

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('firstname', 'LIKE', "%$search%")
              ->orWhere('lastname', 'LIKE', "%$search%")
              ->orWhere('email', 'LIKE', "%$search%")
              ->orWhere('mobile', 'LIKE', "%$search%")
              ->orWhere('teacher_code', 'LIKE', "%$search%");
        });
    }

    $departments = Department::all();
    $teachers = $query->paginate(10)->withQueryString();

    if ($request->ajax()) {
        return view('partials.teacher_table', compact('teachers'))->render();
    }

    return view('teacher', compact('teachers', 'departments'));
}


    public function store(Request $request)
{
    $teacher = Auth::user();
    $request->validate([
        "firstname" => "required",
        "middlename" => "required",
        "lastname" => "required",
        "gender" => "required",
        "mobile" => "required",
        "email" => "required|unique:teachers,email",
        "password" => "required",
        "teacher_code" => "required",
        "deptId" => "required",
    ]);
    $teacher = Teacher::create([
        'firstname' => $request->firstname,
        'middlename' => $request->middlename,
        'lastname' => $request->lastname,
        'gender' => $request->gender,
        'mobile' => $request->mobile,
        'email' => $request->email,
        'password' => Hash::make($request->password), 
        'teacher_code' => $request->teacher_code,
        'deptId' => $request->deptId,
        "branch_id" =>$teacher->branch_id,

    ]);
    Loggins::create([
        'title' => 'New Registration',
        'action' => 'New Teacher '.$request->firstname."  ".$request->middlename." is registered",
    ]);

    if ($teacher) {
        return redirect()->back()->with("success", "New Teacher is Added Successfully");
    }

    return redirect()->back()->with("error", "Failed to add teacher, please try again.");
}
    public function edit($id){
        $teacher = Teacher::findOrFail($id);
        return view("teacherEdit",compact("teacher"));

    }
     public function block($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found.');
        }

        $teacher->status = 'Blocked';
        $teacher->save();
        Loggins::create([
        'title' => 'New Blocking',
        'action' => 'Teacher '.$teacher->firstname."  ".$teacher->lastname." is Blocked",
        ]);

        return redirect()->route('teachers.index')->with('success', $teacher->firstname."  ".$teacher->lastname.' is successful blocked.');
    }


    public function unblock($id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found.');
        }

        $teacher->status = 'Active';
        $teacher->save();
        Loggins::create([
        'title' => 'New Unblocking',
        'action' => 'Teacher '.$teacher->firstname."  ".$teacher->lastname." is unblocked",
        ]);

        return redirect()->route('teachers.index')->with('success', $teacher->firstname."  ".$teacher->lastname.' is successful unblocked.');
    }
    public function update(Request $request,$id){
        $request->validate([
            "firstname" => "required",
            "middlename" => "required",
            "lastname" => "required",
            "email" => "required",
            "mobile" => "required",
            "gender" => "required",
            "teacher_code" => "required",
            "deptId" => "required",
            "role" => "required"
        ]);
        Loggins::create([
        'title' => 'New Updating',
        'action' => 'Teacher '.$request->firstname."  ".$request->lastname." is updated someinfo",
        ]);
        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());
        return redirect()->route("teachers.index")->with("success","Information Updated success");

    }
    public function template(){
            return Excel::download(new TeacherTemplateExport,"teacher_format.xlsx");
        }
    public function teacherDash(){
         $teacher = Auth::user();


    $subjectCount = DB::table('subjects')
    ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
    ->where('teacher_id', $teacher->id)
    ->where('semesters.status', 'Active')
    ->selectRaw("
        COUNT(
            DISTINCT
            CASE
                WHEN subjects.group_name IS NOT NULL
                THEN CONCAT('GROUP_', subjects.group_name)
                ELSE CONCAT('SUBJECT_', subjects.id)
            END
        ) as total_subjects
    ")
    ->value('total_subjects');

    $periodCount = DB::table('timetables')
    ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
    ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
    ->where('subjects.teacher_id', $teacher->id)
    ->where('semesters.status', 'Active')
    ->count();


    // Vipindi kwa siku (kwa ajili ya chart)
    $periodsPerDay = DB::table('timetables')
    ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
    ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
    ->join('days', 'timetables.day_id', '=', 'days.id')
    ->where('subjects.teacher_id', $teacher->id)
    ->where('semesters.status', 'Active')
    ->select('days.day_name', DB::raw('COUNT(timetables.id) as total_periods'))
    ->groupBy('days.day_name')
    ->orderByRaw("FIELD(days.day_name, 'Monday','Tuesday','Wednesday','Thursday','Friday')")
    ->get();


    // Andaa data za chart
    $labels = $periodsPerDay->pluck('day_name');
    $periods = $periodsPerDay->pluck('total_periods');

    // Tuma data kwenye view
    return view('teacherDash', compact('subjectCount', 'periodCount', 'labels', 'periods'));
}
   

     public function superdash()
{
    $todayDate = Carbon::today()->toDateString();
    $todayName = Carbon::now()->format('l');
    $teacher = Auth::user();

    
    $holiday = DB::table('holidays')
        ->where('date', $todayDate)
        ->first();

    $showGraph = true;

    if ($holiday) {
        $showGraph = false;
    }

    // TOTAL LESSONS
    $totalLessons = DB::table('timetables')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->where('days.day_name', $todayName)
        ->count();

    // TAUGHT LESSONS
    $taughtLessons = DB::table('teacher_attendances')
        ->join('timetables', 'teacher_attendances.timetable_id', '=', 'timetables.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->where('teacher_attendances.date', $todayDate)
        ->where('teacher_attendances.status', 'present')
        // ->where('teacher_attendances.branch_id', $teacher->branch_id)
        ->where('days.day_name', $todayName)
        ->count();

    $notTaughtLessons = $totalLessons - $taughtLessons;

    // GRAPH DATA
    $coursesData = DB::table('timetables')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->leftJoin('teacher_attendances', function ($join) use ($todayDate) {
            $join->on('timetables.id', '=', 'teacher_attendances.timetable_id')
                 ->where('teacher_attendances.date', '=', $todayDate);
        })
        ->where('days.day_name', $todayName)
        // ->where('timetables.branch_id', $teacher->branch_id)

        ->select(
            'courses.course_code','courseName',
            DB::raw("SUM(CASE WHEN teacher_attendances.status = 'present' THEN 1 ELSE 0 END) as taught"),
            DB::raw("COUNT(timetables.id) as total")
        )
        ->groupBy('courses.course_code','courseName')
        ->orderBy('courses.course_code')
        ->get();

    $labels = [];
    $taughtData = [];
    $notTaughtData = [];

    foreach ($coursesData as $course) {
        $labels[] = $course->course_code;
        $taughtData[] = $course->taught;
        $notTaughtData[] = $course->total - $course->taught;
    }

    return view("supervdash", compact(
        'totalLessons',
        'taughtLessons',
        'notTaughtLessons',
        'labels',
        'taughtData',
        'notTaughtData',
        'showGraph',
        'coursesData'
    ));
}

   public function report7(Request $request)
{
    $teacher = Auth::user();
    $courses = DB::table('courses')->get();
    $semesters = DB::table('semesters')->where('status', 'Active')->get();

    $selectedCourse = $request->course_id;
    $selectedNta = $request->nta_level;
    $selectedSemester = $request->semester_id;
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $reports = collect();

    if ($request->hasAny(['course_id','nta_level','semester_id','start_date','end_date'])) {

        // Start query from subjects so we can get all subjects even if teacher_attendances missing
        $query = DB::table('subjects')
            ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
            ->join('courses', 'subjects.course_id', '=', 'courses.id')
            ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
            ->leftJoin('teacher_attendances', function($join) use ($startDate, $endDate){
                $join->on('subjects.id', '=', 'teacher_attendances.subject_id');
                if($startDate && $endDate){
                    $join->whereBetween('teacher_attendances.date', [$startDate, $endDate]);
                }
            })
            ->select(
                'subjects.subjectName',
                'subjects.subjectCode',
                'subjects.nta_level',
                'semesters.semName as semester',
                'teachers.firstname',
                'teachers.middlename',
                'teachers.lastname',
                DB::raw("SUM(CASE WHEN teacher_attendances.status = 'present' THEN 1 ELSE 0 END) as total_taught"),
                DB::raw("SUM(CASE WHEN teacher_attendances.status = 'absent' THEN 1 ELSE 0 END) as total_not_taught"),
                DB::raw("COUNT(teacher_attendances.id) as total_sessions")
            )
            ->groupBy(
                'subjects.id',
                'subjects.subjectName',
                'subjects.subjectCode',
                'subjects.nta_level',
                'semesters.semName',
                'teachers.firstname',
                'teachers.middlename',
                'teachers.lastname'
            );

        if ($selectedCourse) {
            $query->where('subjects.course_id', $selectedCourse)->where("subjects.branch_id",$teacher->branch_id);
        }

        if ($selectedNta) {
            $query->where('subjects.nta_level', $selectedNta)->where("subjects.branch_id",$teacher->branch_id);
        }

        if ($selectedSemester) {
            $query->where('subjects.semester_id', $selectedSemester);
        }

        $reports = $query->where("subjects.branch_id",$teacher->branch_id)->get()->map(function ($item) {
            $item->percentage = $item->total_sessions > 0
                ? round(($item->total_taught / $item->total_sessions) * 100, 2)
                : 0;
            return $item;
        });
    }

    return view('report7', compact(
        'reports',
        'courses',
        'semesters',
        'selectedCourse',
        'selectedNta',
        'selectedSemester',
        'startDate',
        'endDate'
    ));
}
    public function teachersubject()
{
    $teacher = Auth::user();

    $subjectsRaw = DB::table('subjects')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->select(
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.group_name',
            'courses.short_name',
            'subjects.nta_level',
            'semesters.semName'
        )
        ->where('subjects.teacher_id', $teacher->id)
        ->where('subjects.branch_id', $teacher->branch_id)
        ->where('semesters.status', 'Active')
        ->get();

    $grouped = [];

    foreach ($subjectsRaw as $item) {

        // NTA Prefix
        $prefix = '';
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
        }

        // Semester Roman
        $semesterRoman = '';

        if (str_contains($item->semName, '1')) {
            $semesterRoman = 'I';
        } elseif (str_contains($item->semName, '2')) {
            $semesterRoman = 'II';
        } elseif (str_contains($item->semName, '3')) {
            $semesterRoman = 'III';
        } elseif (str_contains($item->semName, '4')) {
            $semesterRoman = 'IV';
        }

        // Mfano: BTCPA-I
        $fullCourse = $prefix . $item->short_name .$semesterRoman;

        $key = $item->group_name ?? $item->subjectName;

        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'subjectName' => $item->group_name ?? $item->subjectName,
                'subjectCode' => $item->subjectCode,
                'courses' => []
            ];
        }

        if (!in_array($fullCourse, $grouped[$key]['courses'])) {
            $grouped[$key]['courses'][] = $fullCourse;
        }
    }

    return view('teachersubject', [
        'subjects' => array_values($grouped)
    ]);
}
 public function teacherTimetable()
{
    $teacher = Auth::user();

    /*
    =========================
    TIMETABLE ENTRIES
    =========================
    */

    $timetableEntries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->select(
            'timetables.id as timetable_id',
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.nta_level',
            'semesters.semName',
            'subjects.group_name',
            'courses.short_name',
            'teachers.firstname',
            'teachers.middlename',
            'teachers.lastname',
            'teachers.mobile',
            'rooms.name as room_name'
        )
        ->where('subjects.teacher_id', $teacher->id)
        ->where('subjects.branch_id', $teacher->branch_id)
        ->where('semesters.status', 'Active')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get()
        ->map(function ($item) {

            // PREFIX
            $prefix = '';
            switch ($item->nta_level) {
                case "NTA-4": $prefix = 'BTC'; break;
                case "NTA-5": $prefix = 'TC'; break;
                case "NTA-6": $prefix = 'OD'; break;
                case "NTA-7": $prefix = 'HD'; break;
                case "NTA-8": $prefix = 'B'; break;
            }

            // SEMESTER → ROMAN
            $semesterRoman = '';
            if (str_contains($item->semName, '1')) {
                $semesterRoman = 'I';
            } elseif (str_contains($item->semName, '2')) {
                $semesterRoman = 'II';
            }elseif (str_contains($item->semName, '3')) {
                $semesterRoman = 'III';
            }elseif (str_contains($item->semName, '4')) {
                $semesterRoman = 'IV';
            }

            // FINAL NAME
            $item->fullCourseName = $prefix . $item->short_name . $semesterRoman;

            return $item;
        });

    /*
    =========================
    GROUP COURSES
    =========================
    */

    $groupCourses = DB::table('subjects')
        ->join('courses','subjects.course_id','=','courses.id')
        ->join('semesters','subjects.semester_id','=','semesters.id')
        ->whereNotNull('subjects.group_name')
        ->select(
            'subjects.group_name',
            'subjects.nta_level',
            'courses.short_name',
            'semesters.semName'
        )
        ->get()
        ->map(function($item){

            // PREFIX
            $prefix = '';
            switch ($item->nta_level) {
                case "NTA-4": $prefix = 'BTC'; break;
                case "NTA-5": $prefix = 'TC'; break;
                case "NTA-6": $prefix = 'OD'; break;
                case "NTA-7": $prefix = 'HD'; break;
                case "NTA-8": $prefix = 'B'; break;
            }

            // SEMESTER → ROMAN
            $semesterRoman = '';
            if (str_contains($item->semName, '1')) {
                $semesterRoman = 'I';
            } elseif (str_contains($item->semName, '2')) {
                $semesterRoman = 'II';
            }elseif (str_contains($item->semName, '3')) {
                $semesterRoman = 'III';
            }elseif (str_contains($item->semName, '4')) {
                $semesterRoman = 'IV';
            }

            // FINAL NAME
            $item->courseName = $prefix . $item->short_name . $semesterRoman;

            return $item;
        })
        ->groupBy('group_name')
        ->map(function($items){
            return $items->pluck('courseName')->unique()->values();
        });

    /*
    =========================
    TIMESLOTS
    =========================
    */

    $timeslots = DB::table('timeslots')
        ->orderBy('start_time')
        ->get()
        ->map(function ($t) {
            return [
                'start' => $t->start_time,
                'end' => $t->end_time
            ];
        });

    /*
    =========================
    FINAL TIMETABLE
    =========================
    */

    $timetable = [
        'timeslots' => $timeslots,
        'entries' => $timetableEntries->groupBy('day_name')
    ];

    return view('teachertbl', compact('timetable','groupCourses'));
}
        public function profile(){
            return view("profile");
        }
        public function adprofile(){
            $timetable = SystemTimetable::first();
            $teacher = Auth::guard('teacher')->user();
            return view("adminprofile",compact("timetable","teacher"));
        }
        public function updateProfile(Request $request)
{
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'required|string|max:255',
        'email'     => 'required|email|unique:teachers,email,' . Auth::id(),
        'mobile'    => 'required|string|max:20',
        'gender'    => 'required|string',
    ]);

    $user = Teacher::find(Auth::id()); // ✅ Hapa ndipo siri ya mafanikio
    if (!$user) {
        return back()->withErrors(['error' => 'Teacher not found']);
    }

    $user->update([
        'firstname' => $request->firstname,
        'lastname'  => $request->lastname,
        'email'     => $request->email,
        'mobile'    => $request->mobile,
        'gender'    => $request->gender,
    ]);

    return back()->with('success', 'Profile updated successfully!');
}



    // Change Password
    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:4',
        'confirm_password' => 'required|same:new_password',
    ]);

    $user = Teacher::find(Auth::id()); // ✅ badala ya Auth::user()
    if (!$user) {
        return back()->withErrors(['error' => 'Teacher not found']);
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password changed successfully!');
}
    public function viewtsub($id)
{
    $teacher = Teacher::findOrFail($id);

    $subjectsRaw = DB::table('subjects')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->select(
            'subjects.subjectName',
            'subjects.group_name',
            'courses.short_name',
            'subjects.nta_level',
            'subjects.subjectCode',
            'semesters.status',
            'semesters.semName'
        )
        ->where('subjects.teacher_id', $id)
        ->where('semesters.status', 'Active')
        ->orderBy('subjects.group_name')
        ->get();

    $groupedSubjects = [];

foreach ($subjectsRaw as $item) {

    // PREFIX ya NTA
    $prefix = match ($item->nta_level) {
        "NTA-4" => 'BTC',
        "NTA-5" => 'TC',
        "NTA-6" => 'OD',
        "NTA-7" => 'HD',
        "NTA-8" => 'B',
        default => ''
    };

    $semesterRoman = match (true) {
        str_contains($item->semName, '1') => 'I',
        str_contains($item->semName, '2') => 'II',
        str_contains($item->semName, '3') => 'III',
        str_contains($item->semName, '4') => 'IV',
        default => ''
    };

    $courseName = $prefix . $item->short_name . $semesterRoman;

   if (!empty($item->group_name)) {

    $key = 'GROUP_' . $item->group_name;

    if (!isset($groupedSubjects[$key])) {
        $groupedSubjects[$key] = [
            'subjectName' => $item->group_name,
            'courses' => [],
            'subjectCode' => $item->subjectCode,
            'nta_level' => $item->nta_level,
            'semester' => $semesterRoman
        ];
    }

    // ✅ ADD COURSE + SUBJECT CODE
    $courseWithCode = $courseName . ' (' . $item->subjectCode . ')';

    if (!in_array($courseWithCode, $groupedSubjects[$key]['courses'])) {
        $groupedSubjects[$key]['courses'][] = $courseWithCode;
    }

} else {

        // 🔥 SINGLE SUBJECT (NO GROUPING)
        $key = 'SINGLE_' . $item->subjectName . '_' . $item->subjectCode;

        if (!isset($groupedSubjects[$key])) {
            $groupedSubjects[$key] = [
                'subjectName' => $item->subjectName,
                'courses' => [$courseName],
                'subjectCode' => $item->subjectCode,
                'nta_level' => $item->nta_level,
                'semester' => $semesterRoman
            ];
        }
    }
}

    return view('viewtsub', [
        'teacher' => $teacher,
        'subjects' => $groupedSubjects
    ]);
}

public function supervision()
{
    $todayDate = Carbon::today()->toDateString();
    $todayName = Carbon::now()->format('l');
    $teacher = Auth::user();

    $entries = DB::table('timetables')

        ->join('days', 'timetables.day_id', '=', 'days.id')

        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')

        ->join('courses', 'subjects.course_id', '=', 'courses.id')

        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')

        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')

        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')

        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')

        ->leftJoin('teacher_attendances', function ($join) use ($todayDate) {

            $join->on(
                'timetables.id',
                '=',
                'teacher_attendances.timetable_id'
            )
            ->whereDate(
                'teacher_attendances.date',
                $todayDate
            );
        })

        ->where('days.day_name', $todayName)

        ->where('subjects.branch_id', $teacher->branch_id)

        ->where('semesters.status', 'Active')

        ->select(
            'timetables.id as timetable_id',

            'courses.courseName',

            'courses.short_name',

            'subjects.subjectName',

            'subjects.subjectCode',

            'subjects.nta_level',

            'subjects.credit_hour',

            'semesters.semName as semester',

            'days.day_name as day',

            'timeslots.start_time',

            'timeslots.end_time',

            'teachers.firstname',

            'teachers.middlename',

            'teachers.lastname',

            'rooms.name as room_name',

            DB::raw("
                COALESCE(
                    teacher_attendances.status,
                    'absent'
                ) as status
            ")
        )

        ->orderBy('courses.courseName')

        ->orderBy('subjects.nta_level')

        ->orderBy('semesters.id')

        ->orderBy('timeslots.start_time')

        ->get();

    $timetable = $entries
        ->groupBy('courseName')
        ->map(function ($courseGroup) {

            return $courseGroup
                ->groupBy('nta_level')
                ->map(function ($ntaGroup) {

                    return $ntaGroup
                        ->groupBy('semester');
                });
        });

    return view(
        'supervision',
        compact(
            'timetable',
            'todayName'
        )
    );
}
public function teacherTimetable1()
{
    $teacher = Auth::user();

    /*
    =========================
    TIMETABLE ENTRIES
    =========================
    */
    $today = Carbon::today();
     $status = Semester::where("status","Active")->first();
    $timetableEntries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->select(
            'timetables.id as timetable_id',
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.nta_level',
            'semesters.semName',
            'subjects.group_name',
            'courses.short_name',
            'teachers.firstname',
            'teachers.middlename',
            'teachers.lastname',
            'teachers.mobile',
            'rooms.name as room_name'
        )
        ->where('subjects.teacher_id', $teacher->id)
        ->where('subjects.branch_id',$teacher->branch_id)
        ->where('semesters.status', 'Active')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get()
        ->map(function ($item) {

            // PREFIX
            $prefix = '';
            switch ($item->nta_level) {
                case "NTA-4": $prefix = 'BTC'; break;
                case "NTA-5": $prefix = 'TC'; break;
                case "NTA-6": $prefix = 'OD'; break;
                case "NTA-7": $prefix = 'HD'; break;
                case "NTA-8": $prefix = 'B'; break;
            }

            // SEMESTER → ROMAN
            $semesterRoman = '';
            if (str_contains($item->semName, '1')) {
                $semesterRoman = 'I';
            } elseif (str_contains($item->semName, '2')) {
                $semesterRoman = 'II';
            }elseif (str_contains($item->semName, '3')) {
                $semesterRoman = 'III';
            }elseif (str_contains($item->semName, '4')) {
                $semesterRoman = 'IV';
            }

            // FINAL NAME
            $item->fullCourseName = $prefix . $item->short_name . $semesterRoman;

            return $item;
        });

    /*
    =========================
    GROUP COURSES
    =========================
    */

    $groupCourses = DB::table('subjects')
        ->join('courses','subjects.course_id','=','courses.id')
        ->join('semesters','subjects.semester_id','=','semesters.id')
        ->where('subjects.branch_id',$teacher->branch_id)
        ->whereNotNull('subjects.group_name')
        ->select(
            'subjects.group_name',
            'subjects.nta_level',
            'courses.short_name',
            'semesters.semName'
        )
        ->get()
        ->map(function($item){

            // PREFIX
            $prefix = '';
            switch ($item->nta_level) {
                case "NTA-4": $prefix = 'BTC'; break;
                case "NTA-5": $prefix = 'TC'; break;
                case "NTA-6": $prefix = 'OD'; break;
                case "NTA-7": $prefix = 'HD'; break;
                case "NTA-8": $prefix = 'B'; break;
            }

            // SEMESTER → ROMAN
            $semesterRoman = '';
            if (str_contains($item->semName, '1')) {
                $semesterRoman = 'I';
            } elseif (str_contains($item->semName, '2')) {
                $semesterRoman = 'II';
            }elseif (str_contains($item->semName, '3')) {
                $semesterRoman = 'III';
            }elseif (str_contains($item->semName, '4')) {
                $semesterRoman = 'IV';
            }

            // FINAL NAME
            $item->courseName = $prefix . $item->short_name . $semesterRoman;

            return $item;
        })
        ->groupBy('group_name')
        ->map(function($items){
            return $items->pluck('courseName')->unique()->values();
        });

    /*
    =========================
    TIMESLOTS
    =========================
    */

    $timeslots = DB::table('timeslots')
        ->orderBy('start_time')
        ->get()
        ->map(function ($t) {
            return [
                'start' => $t->start_time,
                'end' => $t->end_time
            ];
        });

    /*
    =========================
    FINAL TIMETABLE
    =========================
    */

    $timetable = [
        'timeslots' => $timeslots,
        'entries' => $timetableEntries->groupBy('day_name')
    ];

    return view('teachertbl1', compact('timetable','groupCourses','status','today','teacher'));
}
    public function teachersubjects1(){
        $teacher = Auth::user();

    // Masomo anayofundisha mwalimu aliye login
        $subjects = DB::table('subjects')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->select(
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.subject_type',
            'subjects.required_lab',
            'courses.courseName',
            'semesters.status',
            'semesters.semName',
            'subjects.nta_level'
        )
        ->where('subjects.teacher_id', $teacher->id)
        ->where('subjects.branch_id',$teacher->branch_id)
        ->where("semesters.status","Active")
        ->get();
        return view("teachersubjects1",compact("subjects"));
    }

   

    public function markEmergency(Request $request)
{
    
    DB::table('teacher_attendances')
        ->where('timetable_id', $request->timetable_id)
        ->update([
            'status' => 'emergency',
        ]);

    return back()->with('success', 'Lesson marked as emergency');
}



}
