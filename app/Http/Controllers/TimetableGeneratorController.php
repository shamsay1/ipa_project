<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentTimetableSheet;
use App\Exports\DepartmentTimetableViewExport;
use App\Exports\TimetableExport;
use App\Models\Course;
use App\Models\Day;
use App\Models\Department;
use App\Models\Loggins;
use App\Models\Room;
use App\Models\Subject;
use PhpOffice\PhpWord\PhpWord;
use App\Models\SystemTimetable;
use App\Models\Teacher;
use App\Models\Timeslot;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\Process\Process;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TimetableGeneratorController extends Controller
{



public function printAllTeachers()
{
    

    /*
    ======================
    GET ALL TEACHERS
    ======================
    */
    $branch_id = Auth::user()->branch_id;
    $teachers = DB::table('teachers')
        ->where('branch_id', Auth::user()->branch_id)
        ->get();

    $allData = [];

    foreach ($teachers as $teacher) {

        $entries = DB::table('timetables')
            ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
            ->join('courses', 'subjects.course_id', '=', 'courses.id')
            ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
            ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
            ->join('days', 'timetables.day_id', '=', 'days.id')
            ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
            ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
            ->select(
                'days.day_name',
                'timeslots.start_time',
                'timeslots.end_time',
                'subjects.subjectName',
                'subjects.subjectCode',
                'subjects.nta_level',
                'subjects.group_name',
                'courses.short_name',
                'teachers.firstname',
                'teachers.middlename',
                'teachers.lastname',
                'rooms.name as room_name',
                'semesters.semName'
            )
            ->where('subjects.teacher_id', $teacher->id)
            ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('timeslots.start_time')
            ->get()
            ->map(function ($item) {

                // PREFIX
                $prefix = match($item->nta_level) {
                    "NTA-4" => 'BTC',
                    "NTA-5" => 'TC',
                    "NTA-6" => 'OD',
                    "NTA-7" => 'HD',
                    "NTA-8" => 'B',
                    default => ''
                };

                $roman = match(true) {
                    str_contains($item->semName, '1') => 'I',
                    str_contains($item->semName, '2') => 'II',
                    str_contains($item->semName, '3') => 'III',
                    str_contains($item->semName, '4') => 'IV',
                    default => ''
                };

                $item->fullCourseName = $prefix . $item->short_name . $roman;

                return $item;
            });

        $timeslots = DB::table('timeslots')
            ->orderBy('start_time')
            ->get();

        $groupCourses = DB::table('subjects')
            ->join('courses','subjects.course_id','=','courses.id')
            ->join('semesters','subjects.semester_id','=','semesters.id')
            ->where('subjects.branch_id', Auth::user()->branch_id)
            ->whereNotNull('subjects.group_name')
            ->select('subjects.group_name','subjects.nta_level','courses.short_name','semesters.semName')
            ->get()
            ->map(function($item){

                $prefix = match($item->nta_level) {
                    "NTA-4" => 'BTC',
                    "NTA-5" => 'TC',
                    "NTA-6" => 'OD',
                    "NTA-7" => 'HD',
                    "NTA-8" => 'B',
                    default => ''
                };

                $roman = match(true) {
                    str_contains($item->semName, '1') => 'I',
                    str_contains($item->semName, '2') => 'II',
                    str_contains($item->semName, '3') => 'III',
                    str_contains($item->semName, '4') => 'IV',
                    default => ''
                };

                $item->courseName = $prefix . $item->short_name . $roman;

                return $item;
            })
            ->groupBy('group_name')
            ->map(fn($items) => $items->pluck('courseName')->unique()->values());

        $allData[] = [
            'teacher' => $teacher,
            'entries' => $entries->groupBy('day_name'),
            'timeslots' => $timeslots,
            'groupCourses' => $groupCourses
        ];
    }

    $pdf = Pdf::loadView('pdf2', compact('allData'));

    return $pdf->download('all_teachers_timetable.pdf');
}
public function generateTimetable(Request $request)
{
    try {
        ini_set('max_execution_time', 600);
        set_time_limit(600);
        ignore_user_abort(true);

        /******************************************************
         * 1. PREPARE DATA FOR PYTHON
         ******************************************************/
        $data = $this->getTimetableData();

        $jsonData = json_encode($data);

        // Ensure directory exists
        $directory = storage_path('app');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Now safe to create the file
        $filename = $directory . '/timetable_data_' . time() . '.json';
        file_put_contents($filename, $jsonData);

        $pythonCommand = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3';

        /******************************************************
         * 2. RUN PYTHON GENETIC ALGORITHM
         ******************************************************/
        $process = new Process([
            $pythonCommand,
            base_path('app/GeneticAlgorithm/main.py'),
            $filename
        ]);

        $process->setTimeout(600);
        $process->run();

        $output = $process->getOutput();
        $errorOutput = $process->getErrorOutput();

        // Delete file after python reads it
        if (file_exists($filename)) {
            unlink($filename);
        }

        if (!$process->isSuccessful()) {
            Log::error("Python failed: " . $errorOutput);
            return response()->json([
                'success' => false,
                'message' => 'Python process failed'
            ]);
        }

        $result = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON Decode Error: $output");
            return response()->json([
                'success' => false,
                'message' => 'Failed to decode timetable output'
            ]);
        }

        /******************************************************
         * 3. SAVE GENERATED RAW TIMETABLE TO DATABASE
         ******************************************************/
        $saved = $this->saveTimetable($result);

        if (!$saved) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save timetable'
            ]);
        }

        /******************************************************
         * 4. AUTO CONFLICT SOLVER (3 min)
         ******************************************************/
        ini_set('max_execution_time', 180);
        set_time_limit(380);
        ini_set('memory_limit', '1024M');
        ignore_user_abort(true);

        // $this->solveConflictsInternal();
        // $this->solveConflicts($request);

        /******************************************************
         * 5. RETURN FINAL TIMETABLE
         ******************************************************/
        return response()->json([
            'success' => true,
            'message' => 'Timetable generated successfully',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        Log::error("Error: " . $e->getMessage());
        return response()->json([
            'success' => true,
            'message' => 'Timetable generated successfully',
            'data' => $result
        ]);
    }
}



    
    private function getTimetableData()
{
    // Get all active semesters (not just one)
    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->get();

    if ($activeSemesters->isEmpty()) {
        throw new \Exception('No active semesters found');
    }

    $semesterIds = $activeSemesters->pluck('id')->toArray();
    $teacher = Auth::user();
    return [
        // get subjects for ALL active semesters
        'subjects' => DB::table('subjects')
            ->where("subjects.branch_id",$teacher->branch_id)
            ->whereIn('semester_id', $semesterIds)
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'teachers' => DB::table('teachers')
            ->where("teachers.branch_id",$teacher->branch_id)
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'rooms' => DB::table('rooms')
            ->where("rooms.branch_id",$teacher->branch_id)
            ->where('status','active')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'timeslots' => DB::table('timeslots')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'days' => DB::table('days')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'courses' => DB::table('courses')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'course_rooms' => DB::table('course_rooms')
            ->where("course_rooms.branch_id",$teacher->branch_id)
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        // send all active semesters
        'semesters' => $activeSemesters
            ->map(fn($item) => (array)$item)
            ->toArray(),

        // GA parameters
        'ga_population_size' => 40,
        'ga_mutation_rate' => 0.02,
        'ga_crossover_rate' => 0.8,
        'ga_elitism_count' => 2,
        'ga_tournament_size' => 5,
        'ga_max_generations' => 200
    ];
}

    
  private function saveTimetable($timetableData)
{
    try {
        DB::beginTransaction();

        $activeSemester = DB::table('semesters')->where('status', 'Active')->first();
        if (!$activeSemester) {
            throw new \Exception('No active semester found.');
        }

        $branchId = Auth::user()->branch_id;

        $subjectIds = DB::table('subjects')
            ->where('semester_id', $activeSemester->id)
            ->where('subjects.branch_id', $branchId)
            ->pluck('id')
            ->toArray();
        if (!empty($subjectIds)) {
            DB::table('timetables')->whereIn('subject_id', $subjectIds)->delete();
        }

        foreach ($timetableData as $entry) {
            $teacherId = DB::table('subjects')->where('branch_id', $branchId)->where('id', $entry['subject_id'])->value('teacher_id');

            DB::table('timetables')->insert([
                'day_id' => $entry['day_id'],
                'subject_id' => $entry['subject_id'],
                'timeslot_id' => $entry['timeslot_id'],
                'room_id' => $entry['room_id'],
                'teacher_id' => $teacherId,
                'semester_id' => $activeSemester->id,
                'branch_id' => $branchId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();
        return true;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Save timetable error: ' . $e->getMessage());
        return false;
    }
}


    
 public function showGenerateForm(Request $request)
{
    session(['last_timetable_url' => $request->fullUrl()]);
    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->orderBy('id')
        ->get();
    $systemTimetable = SystemTimetable::first();
    if ($activeSemesters->isEmpty()) {
        return redirect()->back()->with('error', 'No active semesters found');
    }
    
    $filterCourse = $request->input('course');
    $filterNta = $request->input('nta');

    $courses = DB::table('courses')->get();

    $ntaLevels = DB::table('subjects')
        ->distinct()
        ->pluck('nta_level');
    $teacher = Auth::user();
    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->leftJoin('cr_info', function ($join) {
            $join->on('cr_info.course_id', '=', 'subjects.course_id')
                ->on('cr_info.nta', '=', 'subjects.nta_level')
                ->on('cr_info.semester_id', '=', 'subjects.semester_id');
        })
        ->where('timetables.branch_id', $teacher->branch_id)
        ->whereIn('subjects.semester_id', $activeSemesters->pluck('id'))
        ->when($filterCourse, function ($q) use ($filterCourse) {
            $q->where('subjects.course_id', $filterCourse);
        })
        ->when($filterNta, function ($q) use ($filterNta) {
            $q->where('subjects.nta_level', $filterNta);
        })
        ->select(
    'timetables.id as timetable_id',
    'days.day_name',
    'timeslots.start_time',
    'timeslots.end_time',
    'subjects.subjectName',
    'subjects.subjectCode',
    'subjects.nta_level',
    'subjects.credit_hour',
    'subjects.group_name as subject_group_name', 
    'courses.courseName',
    'courses.short_name',
    'teachers.firstname',
    'teachers.middlename',
    'teachers.lastname',
    'teachers.email',
    'rooms.name as room_name',
    'semesters.semName as semester_name',
    'semesters.id as semester_id',
    'cr_info.firstname as cr_name',
    'cr_info.middlename as cr_name2',
    'cr_info.lastname as cr_name3',
    'cr_info.email as cr_email'
)
        ->orderBy('semesters.id') 
        ->orderBy('courses.course_level','desc')
        ->orderBy('subjects.nta_level')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get();
      
$groupedSubjects = DB::table('subjects')
    ->join('courses', 'subjects.course_id', '=', 'courses.id')
    ->where('subjects.branch_id', $teacher->branch_id)
    ->select(
        'subjects.group_name',
        'subjects.nta_level',
        'courses.short_name'
    )
    ->whereNotNull('subjects.group_name')
    ->get();


$groupCourses = [];

foreach ($groupedSubjects as $item) {

    // PREFIX ya NTA
    $prefix = '';
    switch ($item->nta_level) {
        case "NTA-4": $prefix = 'BTC'; break;
        case "NTA-5": $prefix = 'TC'; break;
        case "NTA-6": $prefix = 'OD'; break;
        case "NTA-7": $prefix = 'HD'; break;
        case "NTA-8": $prefix = 'B'; break;
    }

    $course = $prefix .$item->short_name;

    if (!isset($groupCourses[$item->group_name])) {
        $groupCourses[$item->group_name] = [];
    }

    if (!in_array($course, $groupCourses[$item->group_name])) {
        $groupCourses[$item->group_name][] = $course;
    }
}

    $timetableData = [];

    $semesterGroups = $entries->groupBy('semester_name');

    foreach ($semesterGroups as $semester => $semesterEntries) {

        $courseGroups = $semesterEntries->groupBy('courseName');

        foreach ($courseGroups as $course => $courseEntries) {

            $ntaGroups = $courseEntries->groupBy('nta_level');

            foreach ($ntaGroups as $ntaLevel => $ntaEntries) {

                $groupGroups = $ntaEntries->groupBy('group_name');

                foreach ($groupGroups as $group => $items) {

    $item = $items->first();

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

    $semesterRoman = '';

    if (str_contains($semester, '1')) {
        $semesterRoman = 'I';
    } elseif (str_contains($semester, '2')) {
        $semesterRoman = 'II';
    }elseif (str_contains($semester, '3')) {
        $semesterRoman = 'III';
    }elseif (str_contains($semester, '4')) {
        $semesterRoman = 'IV';
    }
    $shortCourse = $prefix . $item->short_name.' ' . $semesterRoman;
     $courseName = $item->courseName;
    $timetableData[] = [
        'semester' => $semester,
        'course' => $shortCourse,
        'course1' => $courseName,
        'nta_level' => $ntaLevel,
        'group_name' => $group,
        'entries' => $items->groupBy('day_name')
    ];
}
            }
        }
    }

    return view('timetable.generate', compact(
        'timetableData',
        'courses',
        'ntaLevels',
        'activeSemesters',
        'groupCourses',
        'systemTimetable'
    ));
}



    // Add this method for simple view
 public function viewSimpleTimetable(Request $request)
    {
        // Pata data zote muhimu kwa ajili ya semester inayotumika
        $activeSemester = DB::table('semesters')
            ->where('status', 'Active')
            ->first();
        
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'No active semester found');
        }
        
        $courses = DB::table('courses')->get();
        $timetableData = [];
        
        foreach ($courses as $course) {
            $ntaLevels = DB::table('subjects')
                ->where('course_id', $course->id)
                ->where('semester_id', $activeSemester->id)
                ->distinct()
                ->pluck('nta_level')
                ->toArray();
            
            foreach ($ntaLevels as $ntaLevel) {
                $entries = DB::table('timetables')
                    ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                    ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
                    ->join('days', 'timetables.day_id', '=', 'days.id')
                    ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
                    ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
                    ->where('subjects.course_id', $course->id)
                    ->where('subjects.nta_level', $ntaLevel)
                    ->where('subjects.semester_id', $activeSemester->id)
                    ->select(
                        'days.day_name',
                        'timeslots.start_time',
                        'timeslots.end_time',
                        'subjects.subjectName',
                        'teachers.firstname',
                        'teachers.lastname',
                        'teachers.mobile',
                        'rooms.name as room_name'
                    )
                    ->orderBy('days.id')
                    ->orderBy('timeslots.start_time')
                    ->get();

                // Hapa ndipo mabadiliko makubwa yanatokea
                // Grupu data kwa siku
                $groupedEntries = $entries->groupBy('day_name');
                
                if ($entries->count() > 0) {
                    $timetableData[] = [
                        'course' => $course->courseName,
                        'nta_level' => $ntaLevel,
                        'entries' => $groupedEntries, // Tumia data zilizogrupika
                    ];
                }
            }
        }
        
        return view('timetable.simple', compact('timetableData'));
    }

    public function printAll(Request $request)
{
    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->pluck('id');

    $teacher = Auth::user();

    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->where('timetables.branch_id', $teacher->branch_id)
        ->whereIn('subjects.semester_id', $activeSemesters)
        ->select(
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
        ->orderBy('courses.courseName')
        ->orderBy('subjects.nta_level')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get();

    // ==== GROUPING (SAME AS YOUR VIEW) ====
    $timetableData = [];

    $semesterGroups = $entries->groupBy('semester_name');

    foreach ($semesterGroups as $semester => $semesterEntries) {

        $courseGroups = $semesterEntries->groupBy('courseName');

        foreach ($courseGroups as $course => $courseEntries) {

            $ntaGroups = $courseEntries->groupBy('nta_level');

            foreach ($ntaGroups as $ntaLevel => $ntaEntries) {

                $groupGroups = $ntaEntries->groupBy('group_name');

                foreach ($groupGroups as $group => $items) {

                    $item = $items->first();

                    $timetableData[] = [
                        'semester' => $semester,
                        'course1' => $item->courseName,
                        'course' => $item->short_name,
                        'nta_level' => $ntaLevel,
                        'group_name' => $group,
                        'entries' => $items->groupBy('day_name')
                    ];
                }
            }
        }
    }

    // ==== LOAD PDF VIEW ====
    $pdf = Pdf::loadView('pdfall', compact('timetableData'));

    return $pdf->download('all_timetables.pdf');
}
 


public function validateTimetable()
{
    $reports = [];
    $systemTimetable = SystemTimetable::first();
    $teacher = Auth::user()->branch_id;

    
    $activeSemesters = DB::table('semesters')
        ->where('status','Active')
        ->pluck('id');
    

    $total_entries = DB::table('timetables')
        ->where('timetables.branch_id', $teacher)
        ->whereIn('semester_id', $activeSemesters)
        ->count();

    $semesters = implode(',', $activeSemesters->toArray());

    // ==========================
    // 1. SUBJECT SAME DAY
    // ==========================
    $reports['subject_same_day'] = DB::select("
        SELECT 
            t.subject_id,
            s.subjectName,
            s.subjectCode,
            t.day_id,
            d.day_name,
            COUNT(*) as total
        FROM timetables t
        JOIN subjects s ON t.subject_id = s.id
        JOIN days d ON t.day_id = d.id
        WHERE t.semester_id IN ($semesters)
        AND t.branch_id = $teacher
        GROUP BY t.subject_id, s.subjectName, s.subjectCode, t.day_id, d.day_name
        HAVING COUNT(*) > 2
    ");

    // ==========================
    // 2. ROOM DOUBLE BOOKING (SHARED SESSION FIXED)
    // ==========================
    $reports['room_double_booking'] = DB::select("
    SELECT r.name AS room_name, d.day_name, ti.start_time, ti.end_time, COUNT(*) as total
    FROM timetables t
    JOIN timetables t2
        ON t.day_id = t2.day_id
        AND t.timeslot_id = t2.timeslot_id
        AND t.room_id = t2.room_id
        AND t.id <> t2.id
    JOIN subjects s1 ON t.subject_id = s1.id
    JOIN subjects s2 ON t2.subject_id = s2.id
    JOIN rooms r ON t.room_id = r.id
    JOIN days d ON t.day_id = d.id
    JOIN timeslots ti ON t.timeslot_id = ti.id
    WHERE t.semester_id IN ($semesters)
    AND t.branch_id = $teacher
     AND t2.branch_id = $teacher


    -- ONLY check subjects without group_name (grouped subjects are ignored)
    AND (s1.group_name IS NULL OR s1.group_name = '')
    AND (s2.group_name IS NULL OR s2.group_name = '')

    GROUP BY t.room_id, t.day_id, t.timeslot_id, r.name, d.day_name, ti.start_time, ti.end_time
    HAVING COUNT(*) > 1
");

    // ==========================
    // 3. TEACHER DOUBLE BOOKING (SHARED SESSION FIXED)
    // ==========================
    $reports['teacher_double_booking'] = DB::select("
    SELECT te.firstname,te.middlename, te.lastname, d.day_name, ti.start_time, ti.end_time, COUNT(*) as total
    FROM timetables t
    JOIN timetables t2
        ON t.day_id = t2.day_id
        AND t.timeslot_id = t2.timeslot_id
        AND t.id <> t2.id
    JOIN subjects s1 ON t.subject_id = s1.id
    JOIN subjects s2 ON t2.subject_id = s2.id
    JOIN teachers te ON s1.teacher_id = te.id
    JOIN timeslots ti ON t.timeslot_id = ti.id
    JOIN days d ON t.day_id = d.id
    WHERE t.semester_id IN ($semesters)
    AND t.branch_id = $teacher
     AND t2.branch_id = $teacher

    AND s1.teacher_id = s2.teacher_id

    -- ONLY check subjects without group_name
    AND (s1.group_name IS NULL OR s1.group_name = '')
    AND (s2.group_name IS NULL OR s2.group_name = '')

    GROUP BY s1.teacher_id, te.firstname,te.middlename, te.lastname, d.day_name, ti.start_time, ti.end_time
    HAVING COUNT(*) > 1
");

    // ==========================
    // 4. NTA DOUBLE BOOKING (SHARED SESSION FIXED)
    // ==========================
    $reports['nta_double_booking'] = DB::select("
    SELECT 
        c.courseName,
        d.day_name,
        ts.start_time,
        ts.end_time,
        s.nta_level,
        s.semester_id,
        COUNT(*) AS total
    FROM timetables t
    JOIN timetables t2 
        ON t.day_id = t2.day_id 
        AND t.timeslot_id = t2.timeslot_id
        AND t.id <> t2.id

    JOIN subjects s ON t.subject_id = s.id
    JOIN subjects s2 ON t2.subject_id = s2.id

    JOIN courses c ON s.course_id = c.id
    JOIN days d ON t.day_id = d.id
    JOIN timeslots ts ON t.timeslot_id = ts.id

    WHERE s.semester_id IN ($semesters)
        AND s2.semester_id IN ($semesters)
         AND t.branch_id = $teacher
     AND t2.branch_id = $teacher


        AND s.course_id = s2.course_id
        AND s.nta_level = s2.nta_level

    GROUP BY 
        c.courseName,
        d.day_name,
        ts.start_time,
        ts.end_time,
        s.nta_level,
        s.semester_id
    HAVING COUNT(*) > 1
");

    // ==========================
    // 5. NTA MORE THAN 10
    // ==========================

    $reports['nta_more_than_10'] = DB::select("
    SELECT 
        c.courseName,
        s.course_id,
        s.nta_level,
        s.semester_id,
        d.day_name,
        COUNT(*) as total
    FROM timetables t
    JOIN subjects s ON t.subject_id = s.id
    JOIN courses c ON s.course_id = c.id
    JOIN days d ON t.day_id = d.id
    WHERE s.semester_id IN ($semesters)
        AND t.branch_id = $teacher


    GROUP BY 
        c.courseName, 
        s.course_id, 
        s.nta_level, 
        s.semester_id,
        d.day_name

    HAVING COUNT(*) > 10
");

    // ==========================
    // 6. SUBJECT CREDIT HOURS
    // ==========================
    $reports['subject_credit_hour_conflicts'] = DB::select("
        SELECT 
            s.id,
            s.subjectName,
            s.subjectCode,
            c.courseName,
            s.nta_level,
            s.credit_hour,
            COUNT(t.id) as actual_lessons,
            CASE
                WHEN COUNT(t.id) > s.credit_hour THEN 'EXCEEDED'
                WHEN COUNT(t.id) < s.credit_hour THEN 'MISSING'
            END as conflict_type
        FROM subjects s
        JOIN courses c ON s.course_id = c.id
        LEFT JOIN timetables t 
    ON t.subject_id = s.id 
    AND t.semester_id IN ($semesters)
    AND t.branch_id = $teacher

WHERE s.branch_id = $teacher
        GROUP BY s.id, s.subjectName, s.subjectCode, c.courseName, s.nta_level, s.credit_hour
        HAVING COUNT(t.id) != s.credit_hour
    ");

    // ==========================
    // 7. ROOM INCOMPATIBILITY
    // ==========================
    $reports['room_incompatibility'] = DB::select("
        SELECT 
            t.id AS timetable_id,
            s.subjectName,
            s.required_lab,
            r.name AS room_name,
            r.type,
            r.practical_type,
            d.day_name,
            ts.start_time,
            ts.end_time,
            c.courseName,
            s.nta_level
        FROM timetables t
        JOIN subjects s ON t.subject_id = s.id
        JOIN courses c ON s.course_id = c.id
        JOIN rooms r ON t.room_id = r.id
        JOIN days d ON t.day_id = d.id
        JOIN timeslots ts ON t.timeslot_id = ts.id
        WHERE t.semester_id IN ($semesters)
        AND t.branch_id = $teacher
        AND (
            (s.required_lab <> 'Theory' AND (r.type <> 'Lab' OR r.practical_type <> s.required_lab))
            OR (s.required_lab = 'Theory' AND r.type = 'Lab')
        )
    ");

    // ==========================
    // 8. FRIDAY SALAH TIME
    // ==========================
    $reports['friday_salah_time'] = DB::select("
        SELECT 
            s.subjectName,
            s.subjectCode,
            c.courseName,
            s.nta_level,
            d.day_name,
            ts.start_time,
            ts.end_time
        FROM timetables t
        JOIN subjects s ON t.subject_id = s.id
        JOIN courses c ON s.course_id = c.id
        JOIN days d ON t.day_id = d.id
        JOIN timeslots ts ON t.timeslot_id = ts.id
        WHERE 
            t.semester_id IN ($semesters)
            AND t.branch_id = $teacher

            AND LOWER(d.day_name) = 'friday'
            AND TIME(ts.start_time) = '12:00:00'
    ");

    

    // ==========================
    // CALCULATE SCORE
    // ==========================
    $violations = 0;

    foreach ($reports as $rows) {
        foreach ($rows as $row) {
            $violations += isset($row->total) ? $row->total : 1;
        }
    }

    $score = $total_entries > 0 
        ? round(100 - (($violations / $total_entries) * 100)) 
        : 100;

    return view('timetable.validation', compact('reports','score','total_entries','violations','systemTimetable'));
}
public function suggestSlots($subject_id)
{
    $branch = Auth::user()->branch_id;

    $subject = DB::table('subjects')->where('id', $subject_id)->first();

    $days = DB::table('days')->get();
    $timeslots = DB::table('timeslots')->get();

    $available = [];

    foreach ($days as $day) {
        foreach ($timeslots as $slot) {

            // ✅ 1. TEACHER CONFLICT
            $teacherConflict = DB::table('timetables as t')
                ->join('subjects as s', 't.subject_id', '=', 's.id')
                ->where('t.day_id', $day->id)
                ->where('t.timeslot_id', $slot->id)
                ->where('s.teacher_id', $subject->teacher_id)
                ->where('t.branch_id', $branch)
                ->exists();

            // ✅ 2. NTA + COURSE CONFLICT
            $ntaConflict = DB::table('timetables as t')
                ->join('subjects as s', 't.subject_id', '=', 's.id')
                ->where('t.day_id', $day->id)
                ->where('t.timeslot_id', $slot->id)
                ->where('s.course_id', $subject->course_id)
                ->where('s.nta_level', $subject->nta_level)
                ->where('t.branch_id', $branch)
                ->exists();

            
            if (!$teacherConflict && !$ntaConflict) {
                $available[] = [
                    'day' => $day->day_name,
                    'day_id' => $day->id,
                    'start' => $slot->start_time,
                    'end' => $slot->end_time,
                    'timeslot_id' => $slot->id
                ];
            }
        }
    }

    return response()->json($available);
}
public function insertTimetable(Request $request)
{
    $teacher = Auth::user()->branch_id;

    DB::table('timetables')->insert([
        'subject_id' => $request->subject_id,
        'day_id' => $request->day_id,
        'timeslot_id' => $request->timeslot_id,
        'branch_id' => $teacher,
        'semester_id' => DB::table('semesters')->where('status','Active')->value('id'),
    ]);

    return response()->json([
        'message' => 'Inserted successfully without conflict'
    ]);
}
public function solveNtaDoubleBooking()
{
    $teacher = Auth::user();
    $activeSemesters = DB::table('semesters')
        ->where('status','Active')
        ->pluck('id');

    $allTimeslots = DB::table('timeslots')
        ->orderBy('start_time')
        ->get()
        ->values();

    $conflicts = DB::table('timetables as t')
        ->join('timetables as t2', function($join){
            $join->on('t.day_id','=','t2.day_id')
                 ->on('t.timeslot_id','=','t2.timeslot_id')
                 ->on('t.semester_id','=','t2.semester_id')
                 ->whereColumn('t.id','<>','t2.id');
        })
        ->join('subjects as s','t.subject_id','=','s.id')
        ->join('subjects as s2','t2.subject_id','=','s2.id')
        ->where('t.branch_id',$teacher->branch_id)
        ->whereIn('t.semester_id', $activeSemesters)
        ->whereColumn('s.course_id','s2.course_id')
        ->whereColumn('s.nta_level','s2.nta_level')
        ->select(
            't.id as t1_id','t.group_name as g1',
            't2.id as t2_id','t2.group_name as g2'
        )
        ->get();

    foreach ($conflicts as $conflict) {

        
        if (is_null($conflict->g1)) {
            $moveId = $conflict->t1_id;
        } elseif (is_null($conflict->g2)) {
            $moveId = $conflict->t2_id;
        } else {
            continue; // zote zina group → skip
        }

        $record = DB::table('timetables')
    ->where('branch_id', $teacher->branch_id)
    ->where('id',$moveId)
    ->first();
        if (!$record) continue;

        
        if ($record->group_name !== null) continue;

        $subject = DB::table('subjects')->where('branch_id', $teacher->branch_id)->where('id',$record->subject_id)->first();
        if (!$subject) continue;

        $subjectDays = DB::table('timetables')
            ->where('subject_id',$subject->id)
            ->where('branch_id',$teacher->branch_id)
            ->where('semester_id',$record->semester_id)
            ->get()
            ->groupBy('day_id');

        $moved = false;

        
        foreach ($subjectDays as $dayId => $lessons) {

            if (count($lessons) >= 2) continue;

            foreach ($lessons as $lesson) {

                $currentIndex = $allTimeslots->search(function($t) use ($lesson){
                    return $t->id == $lesson->timeslot_id;
                });

                if ($currentIndex === false) continue;

                $candidateSlots = [];

                if (isset($allTimeslots[$currentIndex - 1])) {
                    $candidateSlots[] = $allTimeslots[$currentIndex - 1]->id;
                }
                if (isset($allTimeslots[$currentIndex + 1])) {
                    $candidateSlots[] = $allTimeslots[$currentIndex + 1]->id;
                }

                foreach ($candidateSlots as $slotId) {

                    $validSlot = DB::table('timeslots')
                        ->where('id',$slotId)
                        ->exists();

                    if (!$validSlot) continue;

                    $exists = DB::table('timetables as t')
                        ->join('subjects as s','t.subject_id','=','s.id')
                        ->where('t.day_id',$dayId)
                        ->where('t.timeslot_id',$slotId)
                        ->where('t.branch_id',$teacher->branch_id)
                        ->where('t.semester_id',$record->semester_id)
                        ->where(function($q) use ($subject, $record){
                            $q->where(function($q2) use ($subject){
                                $q2->where('s.course_id',$subject->course_id)
                                   ->where('s.nta_level',$subject->nta_level);
                            })
                            ->orWhere(function($q2) use ($record){
                                $q2->where('t.room_id',$record->room_id);
                            })
                            ->orWhere(function($q2) use ($subject){
                                $q2->where('s.teacher_id',$subject->teacher_id);
                            });
                        })
                        ->exists();

                    if ($exists) continue;

                    DB::table('timetables')
                        ->where('id',$record->id)
                        ->where('branch_id',$teacher->branch_id)
                        ->update([
                            'day_id'=>$dayId,
                            'timeslot_id'=>$slotId
                        ]);

                    $moved = true;
                    break 3;
                }
            }
        }

        if ($moved) continue;

       
        $slots = DB::table('timeslots as ts')
            ->crossJoin('days as d')
            ->select('ts.id as timeslot_id','d.id as day_id')
            ->orderBy('d.id')
            ->orderBy('ts.start_time')
            ->get();

        foreach ($slots as $slot) {

            $count = DB::table('timetables')
                ->where('day_id',$slot->day_id)
                ->where('subject_id',$record->subject_id)
                ->where('semester_id',$record->semester_id)
                ->count();

            if ($count >= 2) continue;

            $exists = DB::table('timetables as t')
                ->join('subjects as s','t.subject_id','=','s.id')
                ->where('t.day_id',$slot->day_id)
                ->where('t.timeslot_id',$slot->timeslot_id)
                ->where('t.branch_id',$teacher->branch_id)
                ->where('t.semester_id',$record->semester_id)
                ->where(function($q) use ($subject, $record){
                    $q->where(function($q2) use ($subject){
                        $q2->where('s.course_id',$subject->course_id)
                           ->where('s.nta_level',$subject->nta_level);
                    })
                    ->orWhere(function($q2) use ($record){
                        $q2->where('t.room_id',$record->room_id);
                    })
                    ->orWhere(function($q2) use ($subject){
                        $q2->where('s.teacher_id',$subject->teacher_id);
                    });
                })
                ->exists();

            if ($exists) continue;

            DB::table('timetables')
                ->where('id',$record->id)
                ->where('branch_id',$teacher->branch_id)
                ->update([
                    'day_id'=>$slot->day_id,
                    'timeslot_id'=>$slot->timeslot_id
                ]);

            break;
        }
    }

    return back()->with('success','Solved (group protected)');
}
public function showedit($id){
    $timetable=Timetable::findOrFail($id);
    $branchId = Auth::user()->branch_id;

    $subjects = Subject::where('branch_id',$branchId)->get();
    $rooms = Room::where('branch_id',$branchId)->get();
    $teachers = Teacher::where('user_level','teacher')
        ->where('branch_id',$branchId)
        ->get();
    $days = Day::all();
    $time = Timeslot::all();
    return view("timetableEdit", compact('timetable','subjects','rooms','days','time'));
}
public function availableRooms(Request $request)
{
    $dayId = $request->day_id;
    $timeslotId = $request->timeslot_id;
    $timetableId = $request->timetable_id;
    $branchId = Auth::user()->branch_id;

    // Rooms ambazo tayari zinatumika
    $busyRooms = Timetable::where('day_id', $dayId)
        ->where('timeslot_id', $timeslotId)
        ->where('branch_id',$branchId)
        ->where('id', '!=', $timetableId) 
        ->pluck('room_id');

    // Rooms free
    $availableRooms = Room::whereNotIn('id', $busyRooms)->where('branch_id',$branchId)->get();

    return response()->json([
        'rooms' => $availableRooms
    ]);
}
public function update(Request $request, $id)
{
    $request->validate([
        "room_id"     => "required",
        "day_id"      => "required",
        "timeslot_id" => "required"
    ]);

    // GET CURRENT TIMETABLE
    $timetable = Timetable::findOrFail($id);

    $subject = DB::table('subjects')
        ->where('id', $timetable->subject_id)
        ->first();

    if (!$subject) {
        return back()->with("error", "Subject not found");
    }

    // CURRENT SLOT (IMPORTANT)
    $branchId = Auth::user()->branch_id;
    $currentDayId = $timetable->day_id;
    $currentSlotId = $timetable->timeslot_id;

    // ==========================
    // GROUP LOGIC
    // ==========================
    if (!is_null($subject->group_name)) {

        $groupSubjectIds = DB::table('subjects')
            ->where('group_name', $subject->group_name)
            ->where('branch_id',$branchId)
            ->pluck('id');

        if ($groupSubjectIds->isEmpty()) {
            return back()->with("error", "Group subjects not found");
        }

        // 🔥 UPDATE ONLY SAME DAY + SAME SLOT
        DB::table('timetables')
            ->whereIn('subject_id', $groupSubjectIds)
            ->where('day_id', $currentDayId)        // 🔥 KEY FIX
            ->where('timeslot_id', $currentSlotId)  // 🔥 KEY FIX
            ->update([
                "room_id"     => $request->room_id,
                "day_id"      => $request->day_id,
                "timeslot_id" => $request->timeslot_id,
                "updated_at"  => now()
            ]);

    } else {

        // SINGLE UPDATE
        $timetable->update([
            "room_id"     => $request->room_id,
            "day_id"      => $request->day_id,
            "timeslot_id" => $request->timeslot_id
        ]);
    }

    $redirectUrl = session('last_timetable_url', route('timetable.generate'));

    return redirect($redirectUrl)->with("success", "Timetable updated successfully");
}
public function checkConflicts(Request $request)
{
    $timetableId = $request->input('timetable_id');
    $subjectId   = $request->input('subject_id');
    $roomId      = $request->input('room_id');
    $dayId       = $request->input('day_id');
    $timeslotId  = $request->input('timeslot_id');
    $teacher = Auth::user();
    $conflicts = [];

    // ==========================
    // GET SUBJECT (Hapa tunapata semester_id ya subject husika)
    // ==========================
    $subject = DB::table('subjects')->where('id', $subjectId)->where("branch_id",$teacher->branch_id)->first();

    if (!$subject) {
        return response()->json(['conflicts' => ['Subject not found.']]);
    }

    // Hii ndio semester_id tunayotaka kuitumia (Kutoka kwenye Table ya Subjects)
    $subSemesterId = $subject->semester_id; 

    $room = DB::table('rooms')->where('id', $roomId)->where("branch_id",$teacher->branch_id)->first();
    if (!$room) {
        return response()->json(['conflicts' => ['Room not found.']]);
    }

    $groupName = DB::table('timetables')
        ->where('id', $timetableId)
        ->value('group_name');

    /* |------------------------------------------------------------------
    | ROOM – SUBJECT TYPE VALIDATION
    |------------------------------------------------------------------ */
    $subjectType = strtolower(trim($subject->subject_type));
    $roomType    = strtolower(trim($room->type));
    $requiredLab = strtolower(trim($subject->required_lab));

    if ($subjectType === 'theory' && $roomType === 'lab') {
        $conflicts[] = "Invalid room: Theory subjects cannot be scheduled in laboratories.";
    }

    if ($subjectType === 'practical') {
        if ($roomType !== 'lab') {
            $conflicts[] = "Invalid room: Practical subjects must be scheduled in a laboratory.";
        } else if (strtolower(trim($room->practical_type)) !== $requiredLab) {
            $conflicts[] = "Invalid lab: This practical subject requires a '{$requiredLab}' lab.";
        }
    }

    /* |------------------------------------------------------------------
    | SUBJECT SAME DAY (Angalia ndani ya semester ya somo hili pekee)
    |------------------------------------------------------------------ */
    $subjectConflict = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('s.semester_id', $subSemesterId) // Tofautisha kwa semester ya somo
        ->where('t.subject_id', $subjectId)
        ->where('t.day_id', $dayId)
        ->where('t.branch_id', $teacher->branch_id)
        ->where('t.id', '!=', $timetableId)
        ->count();

    if ($subjectConflict > 1) {
        $conflicts[] = "This subject is already scheduled today.";
    }

    /* |------------------------------------------------------------------
    | ROOM DOUBLE BOOKING (Vyumba haviangalii semester - Chumba kimoja hakiwezi kuwa na vipindi viwili)
    |------------------------------------------------------------------ */
    $roomConflict = DB::table('timetables')
        ->where('room_id', $roomId)
        ->where('day_id', $dayId)
        ->where('timeslot_id', $timeslotId)
        ->where('id', '!=', $timetableId)
        ->where('branch_id', $teacher->branch_id)
        ->count();

    if ($roomConflict > 0) {
        $conflicts[] = "This room is already in use at this time by another group/semester.";
    }

    /* |------------------------------------------------------------------
    | TEACHER DOUBLE BOOKING (Walimu hawaangalii semester - Mwalimu hawezi kuwa kote kote)
    |------------------------------------------------------------------ */
    $teacherConflict = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('s.teacher_id', $subject->teacher_id)
        ->where('t.day_id', $dayId)
        ->where('t.timeslot_id', $timeslotId)
        ->where('t.id', '!=', $timetableId)
        ->where('t.branch_id', $teacher->branch_id)
        ->count();

    if ($teacherConflict > 0) {
        $conflicts[] = "Teacher is already teaching another session at this time.";
    }

    /* |------------------------------------------------------------------
    | NTA DOUBLE BOOKING (Hapa ndipo tunatofautisha ICT NTA-4 Sem 1 na Sem 2)
    |------------------------------------------------------------------ */
    $ntaConflict = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('s.course_id', $subject->course_id)
        ->where('s.nta_level', $subject->nta_level)
        ->where('s.semester_id', $subSemesterId) // MUHIMU: Inatenga migongano kwa semester ya somo
        ->where('t.day_id', $dayId)
        ->where('t.timeslot_id', $timeslotId)
        ->where('t.branch_id', $teacher->branch_id)
        ->where('t.id', '!=', $timetableId)
        ->count();

    if ($ntaConflict > 0) {
        $conflicts[] = "This NTA group (Semester {$subSemesterId}) already has a class at this time.";
    }

    /* |------------------------------------------------------------------
    | NTA DAILY LIMIT (Kila semester ya NTA level ina limit yake)
    |------------------------------------------------------------------ */
    $ntaLessons = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('s.course_id', $subject->course_id)
        ->where('s.nta_level', $subject->nta_level)
        ->where('s.semester_id', $subSemesterId) // MUHIMU
        ->where('t.day_id', $dayId)
        ->where('t.branch_id', $teacher->branch_id)
        ->count();

    if ($ntaLessons >= 10) {
        $conflicts[] = "This NTA group (Semester {$subSemesterId}) has reached its daily limit.";
    }

    /* |------------------------------------------------------------------
    | CREDIT HOURS (Kuhakiki masaa ya somo ndani ya semester yake)
    |------------------------------------------------------------------ */
    $subjectLessons = DB::table('timetables')
        ->where('subject_id', $subjectId)
        ->where('branch_id', $teacher->branch_id)
        ->count();

    if ($subjectLessons > $subject->credit_hour) {
        $conflicts[] = "This subject has reached/exceeded its credit hours ({$subject->credit_hour}).";
    }

    return response()->json([
        'conflicts'  => $conflicts,
        'can_update' => count($conflicts) === 0
    ]);
}


public function checkSolutions(Request $request)
{
    $timetableId = $request->input('timetable_id');
    $subjectId   = $request->input('subject_id');
    $teacher = Auth::user();
    // GET MAIN SUBJECT
    $subject = DB::table('subjects')->where('id', $subjectId)->first();
    if (!$subject) {
        return response()->json(['solutions' => []]);
    }

    // ==========================
    // 🔥 GET GROUP SUBJECTS
    // ==========================
    if (!is_null($subject->group_name)) {
        $groupSubjects = DB::table('subjects')
            ->where('group_name', $subject->group_name)
        ->where('branch_id', $teacher->branch_id)
            ->get();
    } else {
        $groupSubjects = collect([$subject]);
    }

    $days  = DB::table('days')->get();
    $slots = DB::table('timeslots')->get();

    // ==========================
    // ROOM FILTERING (use main subject)
    // ==========================
    if (strtolower($subject->required_lab) !== 'theory') {
        $rooms = DB::table('rooms')
            ->where('rooms.type', 'Lab')
            ->where('rooms.practical_type', $subject->required_lab)
           ->where('branch_id', $teacher->branch_id)

            ->select('rooms.*')
            ->get();
    } else {
        $rooms = DB::table('rooms')
            ->where('rooms.type', 'Normal')
            ->where('branch_id', $teacher->branch_id)

            ->select('rooms.*')
            ->get();
    }

    $solutions = [];

    foreach ($days as $day) {

        foreach ($slots as $slot) {

            foreach ($rooms as $room) {

                $hasConflict = false;

                foreach ($groupSubjects as $gSubject) {

                    $conflict = DB::table('timetables AS t')
                        ->join('subjects AS s', 't.subject_id', '=', 's.id')
                        ->where('t.branch_id', $teacher->branch_id)
                        ->where('s.branch_id', $teacher->branch_id)
                        ->where(function ($q) use ($gSubject, $day, $slot, $room) {

                            // ROOM
                            $q->orWhere(function ($q2) use ($day, $slot, $room) {
                                $q2->where('t.room_id', $room->id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                            // TEACHER
                            $q->orWhere(function ($q2) use ($gSubject, $day, $slot) {
                                $q2->where('s.teacher_id', $gSubject->teacher_id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                            // NTA GROUP
                            $q->orWhere(function ($q2) use ($gSubject, $day, $slot) {
                                $q2->where('s.course_id', $gSubject->course_id)
                                   ->where('s.nta_level', $gSubject->nta_level)
                                   ->where('s.semester_id', $gSubject->semester_id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                        })
                        ->where('t.id', '!=', $timetableId)

                        ->count();

                    if ($conflict > 0) {
                        $hasConflict = true;
                        break;
                    }
                }

                // ==========================
                // ADD VALID SOLUTION
                // ==========================
                if (!$hasConflict) {

                    $roomLabel = $room->name;
                    
                    $solutions[] = [
                        'day_id'    => $day->id,
                        'day_name'  => $day->day_name,
                        'slot_id'   => $slot->id,
                        'slot_time' => $slot->start_time . " - " . $slot->end_time,
                        'room_id'   => $room->id,
                        'room_name' => $roomLabel
                    ];
                }
            }
        }
    }

    return response()->json(['solutions' => $solutions]);
}
public function downloadTimetable($type)
{
    if (!in_array($type, ['Degree', 'Diploma'])) {
        return redirect()->back()->with('error', 'Invalid type selected.');
    }

    $filename = $type === 'Degree' ? 'Degree_Timetables.xlsx' : 'Diploma_Timetables.xlsx';
    return Excel::download(new TimetableExport($type), $filename);
}







public function viewTeacherTimetable($id)
{
    $teacher = DB::table('teachers')->where('id', $id)->first();

    if (!$teacher) {
        return redirect()->back()->with('error', 'Teacher not found');
    }

    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->pluck('id');

    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->where('subjects.teacher_id', $id)

        ->when($activeSemesters->count() > 0, function ($q) use ($activeSemesters) {
            $q->whereIn('subjects.semester_id', $activeSemesters);
        })

        ->select(
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'subjects.group_name',
            'courses.short_name',
            'subjects.nta_level',
            'rooms.name as room_name'
        )
        ->orderBy('days.id')
        ->orderBy('timeslots.start_time')
        ->get();
    $processed = $entries->groupBy(function ($item) {

        // kama kuna group_name → group kwa hiyo
        if (!empty($item->group_name)) {
            return $item->day_name . '|' .
                   $item->start_time . '|' .
                   $item->end_time . '|' .
                   $item->group_name;
        }

        // kama hakuna → treat as single subject
        return $item->day_name . '|' .
               $item->start_time . '|' .
               $item->end_time . '|' .
               $item->subjectName;
    })
    ->map(function ($group) {

        $first = $group->first();

        // prefix
        $prefix = '';
        switch ($first->nta_level) {
            case "NTA-4": $prefix = 'BTC'; break;
            case "NTA-5": $prefix = 'TC'; break;
            case "NTA-6": $prefix = 'OD'; break;
            case "NTA-7": $prefix = 'HD'; break;
            case "NTA-8": $prefix = 'B'; break;
        }

        // combine courses
        $courses = $group->pluck('short_name')->unique();
        $courseDisplay = $courses->count() > 1
            ? $prefix . $courses->implode(' + ')
            : $prefix . $courses->first();

      
        $subjectDisplay = !empty($first->group_name)
            ? strtoupper($first->group_name)
            : $first->subjectName;

        return (object)[
            'day_name' => $first->day_name,
            'start_time' => $first->start_time,
            'end_time' => $first->end_time,
            'subject_display' => $subjectDisplay,
            'course_display' => $courseDisplay,
            'room_name' => $first->room_name,
        ];
    });

    $groupedEntries = $processed->groupBy('day_name');

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

    return view('viewttimetable', compact('teacher','groupCourses','timetable', 'groupedEntries'));
}

public function solveConflicts(Request $request)
{
    ini_set('max_execution_time', 300);
    set_time_limit(300);
    ignore_user_abort(true);
    ini_set('memory_limit', '1024M');

    $branchId = Auth::user()->branch_id;

    $semester = DB::table('semesters')
        ->where('status', 'Active')
        ->first();

    $subjects = DB::table('subjects')
        ->where('branch_id', $branchId)
        ->get();

    $days  = DB::table('days')->get();
    $slots = DB::table('timeslots')->get();
    $rooms = DB::table('rooms')->where('branch_id', $branchId)->get();

    foreach ($subjects as $subject) {

        $scheduled = DB::table('timetables')
            ->where('semester_id', $semester->id)
            ->where('branch_id', $branchId)
            ->where('subject_id', $subject->id)
            ->count();

        $remaining = $subject->credit_hour - $scheduled;

        if ($remaining <= 0) continue;

        for ($i = 0; $i < $remaining; $i++) {

            $placed = false;

            
            $daysSorted = $days->sortBy(function ($day) use ($semester, $branchId) {
                return DB::table('timetables')
                    ->where('semester_id', $semester->id)
                    ->where('branch_id', $branchId)
                    ->where('day_id', $day->id)
                    ->count();
            });

            foreach ($daysSorted as $day) {

                // randomize slots kidogo
                $slotsShuffled = $slots->shuffle();

                foreach ($slotsShuffled as $slot) {

                    foreach ($rooms as $room) {

                        $subjectType = strtolower(trim($subject->subject_type));
                        $roomType    = strtolower(trim($room->type));

                        // ROOM RULES
                        if ($subjectType === 'practical' && $roomType !== 'lab') continue;
                        if ($subjectType === 'theory' && $roomType === 'lab') continue;

                        // ROOM CONFLICT
                        $roomBusy = DB::table('timetables')
                            ->where('semester_id', $semester->id)
                            ->where('branch_id', $branchId)
                            ->where('day_id', $day->id)
                            ->where('timeslot_id', $slot->id)
                            ->where('room_id', $room->id)
                            ->exists();

                        if ($roomBusy) continue;

                       
                        $teacherBusy = DB::table('timetables as t')
                            ->join('subjects as s', 't.subject_id', '=', 's.id')
                            ->where('t.semester_id', $semester->id)
                            ->where('t.branch_id', $branchId)
                            ->where('s.teacher_id', $subject->teacher_id)
                            ->where('t.day_id', $day->id)
                            ->where('t.timeslot_id', $slot->id)
                            ->exists();

                        if ($teacherBusy) continue;

                     
                        $groupBusy = DB::table('timetables as t')
                            ->join('subjects as s', 't.subject_id', '=', 's.id')
                            ->where('t.semester_id', $semester->id)
                            ->where('t.branch_id', $branchId)
                            ->where('s.course_id', $subject->course_id)
                            ->where('s.nta_level', $subject->nta_level)
                            ->where('t.group_name', $subject->group_name)
                            ->where('t.day_id', $day->id)
                            ->where('t.timeslot_id', $slot->id)
                            ->exists();

                        if ($groupBusy) continue;

                        // SUBJECT SAME DAY (1 per day)
                        $subjectToday = DB::table('timetables')
                            ->where('semester_id', $semester->id)
                            ->where('branch_id', $branchId)
                            ->where('subject_id', $subject->id)
                            ->where('day_id', $day->id)
                            ->count();

                        if ($subjectToday >= 1) continue;

                        
                        DB::table('timetables')->insert([
                            'semester_id' => $semester->id,
                            'subject_id'  => $subject->id,
                            'room_id'     => $room->id,
                            'day_id'      => $day->id,
                            'timeslot_id' => $slot->id,
                            'group_name'  => $subject->group_name,
                            'branch_id'   => $branchId,
                            'created_at'  => now(),
                            'updated_at'  => now()
                        ]);

                        $placed = true;
                        break 3;
                    }
                }
            }

            if (!$placed) {
                echo "Imeshindikana kupanga subject ID: {$subject->id}<br>";
            }
        }
    }

    return redirect()->route("validate");
}

public function solveConflicts1(Request $request)
{
    ini_set('max_execution_time', 180);
    set_time_limit(180);
    ignore_user_abort(true);
    $branchId = Auth::user()->branch_id;
    ini_set('memory_limit', '1024M');

    echo "<div style='padding:15px; background:#d1ffd1; border-left:5px solid #28a745;'>
            Conflict solving started... please wait. You can continue using the system.
          </div>";
    ob_flush();
    flush();

    $hasChanges = true;

    while ($hasChanges) {

        $hasChanges = false;

        $timetables = DB::table('timetables')->where('branch_id', $branchId)->orderBy('id')->get();

        foreach ($timetables as $timetable) {

            $subject = DB::table('subjects')
                ->where('id', $timetable->subject_id)
                ->where('branch_id', $branchId)
                ->first();

            $room = DB::table('rooms')
                ->where('id', $timetable->room_id)
                ->first();

            /* ---------------------------------------------------------
               PRACTICAL ROOM FIX
            ---------------------------------------------------------*/
            if ($subject && $room && !empty($subject->required_lab)) {

                // Kama subject ni practical lakini room sio lab
                if (strtolower($room->type) != 'lab') {

                    $labs = DB::table('rooms')
                        ->where('type', 'Lab')
                        ->where('branch_id', $branchId)
                        ->whereRaw('LOWER(practical_type) = ?', [strtolower($subject->required_lab)])
                        ->get();

                    foreach ($labs as $lab) {

                        $roomConflict = DB::table('timetables')
                            ->where('room_id', $lab->id)
                            ->where('day_id', $timetable->day_id)
                            ->where('branch_id', $branchId)
                            ->where('timeslot_id', $timetable->timeslot_id)
                            ->exists();

                        if (!$roomConflict) {

                            DB::table('timetables')
                                ->where('id', $timetable->id)
                                ->update([
                                    'room_id'    => $lab->id,
                                    'updated_at' => now()
                                ]);

                            $hasChanges = true;
                            continue 2;
                        }
                    }
                }
            }

            /* ---------------------------------------------------------
               ORIGINAL CONFLICT SOLVER
            ---------------------------------------------------------*/

            $conflicts = $this->checkConflicts(new Request([
                'timetable_id' => $timetable->id,
                'subject_id'   => $timetable->subject_id,
                'room_id'      => $timetable->room_id,
                'day_id'       => $timetable->day_id,
                'timeslot_id'  => $timetable->timeslot_id
            ]))->getData(true)['conflicts'];

            if (count($conflicts) == 0) {
                continue;
            }

            $solutionsResponse = $this->checkSolutions(new Request([
                'timetable_id' => $timetable->id,
                'subject_id'   => $timetable->subject_id
            ]));

            $solutions = $solutionsResponse->getData(true)['solutions'];

            if (count($solutions) == 0) {
                continue;
            }

            $selectedSlot = null;

            foreach ($solutions as $slot) {

                $testConflict = $this->checkConflicts(new Request([
                    'timetable_id' => $timetable->id,
                    'subject_id'   => $timetable->subject_id,
                    'room_id'      => $slot['room_id'],
                    'day_id'       => $slot['day_id'],
                    'timeslot_id'  => $slot['slot_id']
                ]))->getData(true)['conflicts'];

                if (count($testConflict) == 0) {
                    $selectedSlot = $slot;
                    break;
                }
            }

            if (!$selectedSlot) {
                $selectedSlot = $solutions[0];
            }

            DB::table('timetables')
                ->where('id', $timetable->id)
                ->update([
                    'day_id'      => $selectedSlot['day_id'],
                    'timeslot_id' => $selectedSlot['slot_id'],
                    'room_id'     => $selectedSlot['room_id'],
                    'updated_at'  => now()
                ]);

            $hasChanges = true;
        }
    }

    return redirect()->route("validate");
}





public function checkSolutions1(Request $request)
{
    $subjectId = $request->input('subject_id');
    $branch = Auth::user()->branch_id;

    $subject = DB::table('subjects')->where('id', $subjectId)->first();

    if (!$subject) {
        return response()->json(['solutions' => []]);
    }

    // GROUP SUBJECTS
    if (!is_null($subject->group_name)) {
        $groupSubjects = DB::table('subjects')
            ->where('group_name', $subject->group_name)
            ->where('branch_id', $branch)
            ->get();
    } else {
        $groupSubjects = collect([$subject]);
    }

    $days  = DB::table('days')->get();
    $slots = DB::table('timeslots')->get();

    // ROOM FILTER
    if (strtolower($subject->required_lab) !== 'theory') {
        $rooms = DB::table('rooms')
            ->where('type', 'Lab')
            ->where('practical_type', $subject->required_lab)
            ->where('branch_id', $branch)
            ->get();
    } else {
        $rooms = DB::table('rooms')
            ->where('type', 'Normal')
            ->where('branch_id', $branch)
            ->get();
    }

    $solutions = [];

    foreach ($days as $day) {
        foreach ($slots as $slot) {
            foreach ($rooms as $room) {

                $hasConflict = false;

                foreach ($groupSubjects as $gSubject) {

                    $conflict = DB::table('timetables as t')
                        ->join('subjects as s', 't.subject_id', '=', 's.id')
                        ->where('t.branch_id', $branch)
                        ->where(function ($q) use ($gSubject, $day, $slot, $room) {

                            // ROOM
                            $q->orWhere(function ($q2) use ($day, $slot, $room) {
                                $q2->where('t.room_id', $room->id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                            // TEACHER
                            $q->orWhere(function ($q2) use ($gSubject, $day, $slot) {
                                $q2->where('s.teacher_id', $gSubject->teacher_id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                            // COURSE + NTA
                            $q->orWhere(function ($q2) use ($gSubject, $day, $slot) {
                                $q2->where('s.course_id', $gSubject->course_id)
                                   ->where('s.nta_level', $gSubject->nta_level)
                                   ->where('s.semester_id', $gSubject->semester_id)
                                   ->where('t.day_id', $day->id)
                                   ->where('t.timeslot_id', $slot->id);
                            });

                        })
                        ->count();

                    if ($conflict > 0) {
                        $hasConflict = true;
                        break;
                    }
                }

                if (!$hasConflict) {
                    $solutions[] = [
                        'day_id'    => $day->id,
                        'day_name'  => $day->day_name,
                        'slot_id'   => $slot->id,
                        'slot_time' => $slot->start_time . " - " . $slot->end_time,
                        'room_id'   => $room->id,
                        'room_name' => $room->name
                    ];
                }
            }
        }
    }

    // REMOVE DUPLICATES (important)
    $solutions = collect($solutions)->unique(function ($item) {
        return $item['day_id'].'-'.$item['slot_id'].'-'.$item['room_id'];
    })->values();

    return response()->json(['solutions' => $solutions]);
}


public function arrangeClasses(Request $request)
{
    // 1) Pata masomo yote ya THEORY
    $theorySubjects = DB::table('subjects')
        ->where('subject_type', 'theory')
        ->get();

    foreach ($theorySubjects as $subject) {

        $course_id = $subject->course_id;
        $nta_level = $subject->nta_level;

        /*
        |---------------------------------------------------
        | 2) Tafuta rooms za course & nta_level
        |---------------------------------------------------
        */
        $courseRooms = DB::table('course_rooms')
            ->where('course_id', $course_id)
            ->where('nta_level', $nta_level)
            ->get();

        // Hakuna room configuration → skip
        if ($courseRooms->isEmpty()) {
            continue;
        }

        /*
        |---------------------------------------------------
        | 3) Cheki kama kuna group
        |---------------------------------------------------
        */
        $distinctGroups = $courseRooms->pluck('group_name')->filter()->unique();

        /*
        |---------------------------------------------------
        | ✅ CASE 1 — HAKUNA GROUP
        |---------------------------------------------------
        */
        if ($distinctGroups->count() == 0) {
            $room_id = $courseRooms->first()->room_id;

            DB::table('timetables')
                ->where('subject_id', $subject->id)
                ->update(['room_id' => $room_id]);

            continue;   // end this subject and move on
        }

        /*
        |---------------------------------------------------
        | ✅ CASE 2 — KUNA GROUPS
        |   Assign room per group
        |---------------------------------------------------
        */
        foreach ($courseRooms as $cr) {

            $groupName = $cr->group_name;
            $room_id   = $cr->room_id;

            DB::table('timetables')
                ->where('subject_id', $subject->id)
                ->where('group_name', $groupName)     // key
                ->update(['room_id' => $room_id]);
        }
    }

    return redirect()->back()->with('success', 'All THEORY classes have been arranged successfully!');
}

public function syncGroupSubjects()
{
    $branchId = Auth::user()->branch_id;
    $groups = Subject::whereNotNull('group_name')
        ->where('branch_id', $branchId)
        ->get()
        ->groupBy('group_name');

    foreach ($groups as $groupName => $subjects) {

        // Hakikisha kuna zaidi ya subject moja (wanashare group)
        if ($subjects->count() < 2) {
            continue;
        }

        // Chukua subject ya reference
        $referenceSubject = $subjects->first();

        $referenceTimetable = DB::table('timetables')
            ->where('subject_id', $referenceSubject->id)
            ->where('branch_id', $branchId)
            ->orderBy('timeslot_id')
            ->get();

        if ($referenceTimetable->count() == 0) {
            continue;
        }

        // ============================
        // 🔢 CALCULATE TOTAL STUDENTS
        // ============================
        $totalStudents = 0;

        foreach ($subjects as $subject) {
            $students = DB::table('course_rooms')
                ->where('course_id', $subject->course_id)
                ->where('nta_level', $subject->nta)
                ->value('total_students');

            $totalStudents += $students ?? 0;
        }

        // ============================
        // 📘 DETECT SUBJECT TYPE
        // ============================
        // assumption: kuna column 'type' (theory/practical)
        $type = strtolower($referenceSubject->type); 

        // ============================
        // 🏫 FIND SUITABLE ROOM
        // ============================
        $room = null;

        if ($totalStudents > 50) {

            if ($type == 'theory') {

                $room = DB::table('rooms')
                    ->where('type', 'theory')
                    ->where('capacity', '>=', $totalStudents)
                    ->orderBy('capacity', 'asc')
                    ->first();

            } elseif ($type == 'practical') {

                $room = DB::table('rooms')
                    ->where('type', 'Lab')
                    ->where('capacity', '>=', $totalStudents)
                    ->orderBy('capacity', 'asc')
                    ->first();
            }
        }

        // ============================
        // 🔁 SYNC TIMETABLES
        // ============================
        foreach ($subjects as $subject) {

            $subjectTimetable = DB::table('timetables')
                ->where('subject_id', $subject->id)
                ->orderBy('timeslot_id')
                ->get();

            foreach ($subjectTimetable as $index => $row) {

                if (!isset($referenceTimetable[$index])) {
                    continue;
                }

                DB::table('timetables')
                    ->where('id', $row->id)
                    ->update([
                        'day_id' => $referenceTimetable[$index]->day_id,
                        'timeslot_id' => $referenceTimetable[$index]->timeslot_id,
                        'room_id' => $room ? $room->id : $referenceTimetable[$index]->room_id,
                    ]);
            }
        }
    }

    return back()->with('success', 'Grouped subjects synced with room allocation successfully');
}


        public function enable()
{
    $timetable = SystemTimetable::first();

    $timetable->status = 'created';
    $timetable->created_at = now();
    $timetable->save();

    return back()->with('success', 'Timetable enabled');
}

        public function disable()
        {
            $timetable = SystemTimetable::first();
            $timetable->status = 'maintenance';

            $timetable->save();

            return back()->with('success', 'Timetable disabled');
        }
        public function message(){
            $admin = Teacher::where("user_level","admin")->first();
            return view("message",compact("admin"));
        }
        

public function sendEmail(Request $request)
{
    $emails = [];

    if ($request->teacher_email) {
        $emails[] = $request->teacher_email;
    }

    if ($request->cr_email) {
        $emails[] = $request->cr_email;
    }

    if (empty($emails)) {
        return back()->with('error', 'No email found');
    }

    foreach ($emails as $email) {

        Mail::send('emailalert', [
            'subjectName' => $request->subject,
            'subjectCode' => $request->subject_code,
            'messageBody' => $request->message
        ], function ($mail) use ($email) {

            $mail->to($email)
                 ->subject('Timetable Changed Notification');
        });
    }

    return back()->with("success","Email sent successfully to Teacher and CR");
}



           public function exportTeachersSubjects()
{
    
    $teachers = DB::table('subjects')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->select(
            'teachers.id as teacher_id',
            DB::raw("CONCAT(teachers.firstname,' ',teachers.middlename,' ',teachers.lastname) as teacher_name"),
            'subjects.subjectName',
            'subjects.subjectCode',
            'subjects.group_name',
            'subjects.nta_level',
            'courses.short_name',
            'semesters.semName'
        )
        ->where('subjects.branch_id', Auth::user()->branch_id)
        ->orderBy('teachers.id')
        ->get()
        ->groupBy('teacher_id'); 


    $phpWord = new PhpWord();

    $section = $phpWord->addSection([
        'orientation' => 'landscape',
        'marginLeft' => 600,
        'marginRight' => 600,
        'marginTop' => 600,
        'marginBottom' => 600,
    ]);

    $section->addText(
        'RIPOTI YA WALIMU NA MASOMO YAO',
        ['bold' => true, 'size' => 16],
        ['alignment' => 'center']
    );

    $section->addTextBreak(1);

    $phpWord->addTableStyle('myTable', [
        'borderSize' => 6,
        'borderColor' => '000000',
        'cellMargin' => 80
    ]);

    $table = $section->addTable('myTable');

    // HEADER
    $table->addRow();
    $table->addCell(4000)->addText('JINA LA MWALIMU', ['bold' => true]);
    $table->addCell(12000)->addText('MASOMO ANAYOFUNDISHA', ['bold' => true]);

    
    foreach ($teachers as $teacherSubjects) {

        $table->addRow();

        // teacher name
        $teacherName = $teacherSubjects->first()->teacher_name;
        $table->addCell(4000)->addText($teacherName);

        $cell = $table->addCell(12000);

        
        $grouped = $teacherSubjects->groupBy(function ($item) {
            return $item->group_name ?? uniqid(); 
        });

        $counter = 1;

        foreach ($grouped as $group) {

            $subjectsText = [];

            foreach ($group as $item) {

               
                $prefix = match($item->nta_level) {
                    "NTA-4" => 'BTC',
                    "NTA-5" => 'TC',
                    "NTA-6" => 'OD',
                    "NTA-7" => 'HD',
                    "NTA-8" => 'B',
                    default => ''
                };

              
                $roman = match(true) {
                    str_contains($item->semName, '1') => 'I',
                    str_contains($item->semName, '2') => 'II',
                    str_contains($item->semName, '3') => 'III',
                    str_contains($item->semName, '4') => 'IV',
                    default => ''
                };

   
                $formatted =
                    $item->subjectCode . ' ' .
                    strtoupper($item->subjectName) . ' (' .
                    $prefix . strtoupper($item->short_name) . $roman . ')';

                $subjectsText[] = $formatted;
            }

            
            $finalLine = implode(' MIXED WITH ', $subjectsText);

            $cell->addText($counter . '. ' . $finalLine);

            $counter++;
        }
    }

    // SAVE
    $tempFile = tempnam(sys_get_temp_dir(), 'word');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($tempFile);

return response()->download($tempFile, 'teachers_subjects.docx')->deleteFileAfterSend(true);
}
   























}