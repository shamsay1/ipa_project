<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    public function report(){
        $departments = Department::all();
        $semesters = DB::table('semesters')->orderBy('id', 'asc')->get(); // 🔥 Dynamic semesters
        return view("report",compact("departments","semesters"));
    }
    public function report1(){
        $departments = Department::all();
        $semesters = DB::table('semesters')->orderBy('id', 'asc')->get();
        return view("teachersub",compact("departments","semesters"));
    }
public function teacherLoadReport(Request $request)
{
    $departments = DB::table('departments')->get();
    $semesters = DB::table('semesters')->orderBy('id', 'asc')->get();
    $branchId = Auth::user()->branch_id;
    $query = DB::table('timetables as t')
        ->join('subjects as s', 't.subject_id', '=', 's.id')
        ->join('teachers as tr', 's.teacher_id', '=', 'tr.id')
        ->join('departments as d', 'tr.deptId', '=', 'd.id')
        ->join('timeslots as ts', 't.timeslot_id', '=', 'ts.id')
        ->join('days as dy', 't.day_id', '=', 'dy.id')
        ->select(
            'tr.id as teacher_id',
            DB::raw("CONCAT(tr.firstname, ' ', tr.middlename,' ',tr.lastname) as teacher_name"),
            'd.deptName as department',
            't.day_id',
            'dy.day_name',
            'ts.id as slot_id',
            'ts.start_time',
            'ts.end_time',
            's.semester_id',
            's.id as subject_id'
        )->orderBy("tr.firstname");

    if ($request->filled('department_id')) {
        $query->where('d.id', $request->department_id);
    }

    if ($request->filled('semester')) {
        $query->where('s.semester_id', $request->semester);
    }

    $data = $query->get();

    $report = [];

foreach ($data->groupBy('teacher_id') as $teacherId => $lessons) {

    // 🔥 HII NDIYO MUHIMU (Distinct days per week)
    $totalTeachingDays = $lessons->pluck('day_id')->unique()->count();

    // Total Subjects
    $totalSubjects = $lessons->groupBy('subject_id')->count();

    if ($totalSubjects > 4) {
        $subjectStatus = "Overloaded";
    } elseif ($totalSubjects < 4) {
        $subjectStatus = "Underloaded";
    } else {
        $subjectStatus = "Balanced";
    }

    // Max per day (bado useful)
    $dayCounts = $lessons->groupBy('day_id')->map(fn($items) => $items->count());
    $maxPerDay = $dayCounts->max() ?? 0;
    $maxDayId = $dayCounts->sortDesc()->keys()->first();
    $dayName = $lessons->firstWhere('day_id', $maxDayId)->day_name ?? '-';

    // Evening lessons
    $evening = $lessons
        ->filter(fn($l) => $l->start_time >= '16:00:00' && $l->end_time <= '18:30:00')
        ->count();

    // Full Day Check
    $fullDay = "-";
    $totalSlotsPerDay = DB::table('timeslots')->count();

    foreach ($lessons->groupBy('day_id') as $dayId => $dailyLessons) {
        if ($dailyLessons->count() == $totalSlotsPerDay) {
            $fullDay = "Full Day Load (" . $dailyLessons->first()->day_name . ")";
            break;
        }
    }

    // 🔥 STATUS SASA INATEGEMEA TEACHING DAYS
    $status = "Balanced";

    if ($totalTeachingDays <= 2) {
        $status = "Underloaded";
    } elseif ($totalTeachingDays >= 5) {
        $status = "Overloaded";
    }

    if ($maxPerDay > 5) {
        $status .= " (Heavy $dayName)";
    }

    if ($evening > 3) {
        $status .= " (Too many evening lessons)";
    }

    // Optional: list ya siku
    $daysList = $lessons->pluck('day_name')->unique()->implode(', ');

    // Final report
    $report[] = [
        'teacher_id'          => $teacherId,
        'teacher'             => $lessons->first()->teacher_name,
        'total_teaching_days' => $totalTeachingDays, // 🔥 HII NDIYO MPYA
        'days_list'           => $daysList,          // optional
        'max_per_day'         => $maxPerDay . " ($dayName)",
        'evening_lessons'     => $evening,
        'full_day'            => $fullDay,
        'status'              => $status,
    ];
}

    return view('report', compact('departments', 'semesters', 'report'));
}


   public function loadReport(Request $request)
{
    $departments = Department::all();
    $semesters = DB::table('semesters')->orderBy('id', 'asc')->get(); 

    $query = DB::table('teachers')
        ->leftJoin('subjects', 'subjects.teacher_id', '=', 'teachers.id')
        ->leftJoin('departments', 'teachers.deptId', '=', 'departments.id')
        ->leftJoin('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->where('user_level', 'teacher')
        ->select(
            'teachers.id',
            DB::raw("CONCAT(teachers.firstname, ' ', teachers.lastname) as teacher_name"),
            'departments.deptName as department_name',
            DB::raw('COUNT(subjects.id) as subject_count')
        )
        ->groupBy('teachers.id', 'teachers.firstname', 'teachers.lastname', 'departments.deptName')
        ->orderBy("teachers.firstname");

    if ($request->filled('department_id')) {
        $query->where('teachers.deptId', $request->department_id);
    }

    if ($request->filled('semester')) {
        $query->where('subjects.semester_id', $request->semester);
    }

    $report = $query->get();
    foreach($report as $item) {
        if ($item->subject_count > 6) {
            $item->status = 'Overloaded';
        } elseif ($item->subject_count == 6) {
            $item->status = 'Balanced';
        } else {
            $item->status = 'Underloaded';
        }
    };

    return view('teachersub', compact('departments', 'semesters', 'report'));
}

    public function index1(Request $request)
{
    $departments = Department::all();

    // 🔥 CHUKUA SEMESTER ZOTE ACTIVE
    $activeSemesters = Semester::where('status', 'Active')->pluck('id');
    $branchId = Auth::user()->branch_id;
    $reportType = $request->get('report_type');
    $roomId = $request->get('room_id');
    $report = null;

    $days = DB::table('days')->orderBy('id')->get();
    $timeslots = DB::table('timeslots')->orderBy('id')->get();

    $allRooms = DB::table('rooms')->where("branch_id",$branchId)->select('id','name')->get();

    if ($reportType === 'room' && $roomId) 
    {
        $usage = DB::table('timetables')
            ->join('subjects','timetables.subject_id','=','subjects.id')

            
            ->whereIn('subjects.semester_id', $activeSemesters)
            ->where('timetables.branch_id', $branchId)

            ->where('timetables.room_id', $roomId)
            ->select('timetables.day_id','timetables.timeslot_id')
            ->get();

        $usageMap = [];

        foreach ($usage as $u) {
            $usageMap[$u->day_id][$u->timeslot_id] = 'Used';
        }

        $selectedRoom = DB::table('rooms')->where('id',$roomId)->first();

        $report = [
            'usageMap' => $usageMap,
            'selectedRoom' => $selectedRoom
        ];
    }

    return view('roomusage', compact(
        'departments', 
        'activeSemesters', // optional kama utahitaji view
        'reportType',
        'report',
        'days',
        'timeslots',
        'allRooms'
    ));
}




}
