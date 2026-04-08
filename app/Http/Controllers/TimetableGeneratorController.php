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
use App\Models\Teacher;
use App\Models\Timeslot;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TimetableGeneratorController extends Controller
{
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

        $this->solveConflictsInternal();
        $this->solveConflicts($request);

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

// INTERNAL CONFLICT SOLVER (NO REDIRECT, NO OUTPUT)
    private function solveConflictsInternal()
{
    $maxLoops = 10;

    for ($loop = 0; $loop < $maxLoops; $loop++) {

        $conflictFound = false;

        $subjects = DB::table('subjects')->get();

        $days = DB::table('days')
            ->orderByRaw("FIELD(day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->get();

        $slots = DB::table('timeslots')->orderBy('start_time')->get();

        $weekdayUsage = []; // track used days per course
        $dailyCount = [];   // track vipindi per day

        // GROUP subjects
        $groupedSubjects = $subjects->groupBy(function ($s) {
            return $s->course_id . '_' . $s->nta_level . '_' . $s->semester_id . (!empty($s->group_name) ? '_group_'.$s->group_name : '_single');
        });

        foreach ($groupedSubjects as $groupName => $groupSubjects) {

            $subject = $groupSubjects->first();
            $course = DB::table('courses')->where('id',$subject->course_id)->first();
            if (!$course) continue;

            $level = strtolower($course->course_level);
            $isGroup = $groupSubjects->count() > 1 && !empty($subject->group_name);

            $weekdayKey = $subject->course_id.'_'.$subject->nta_level.'_'.$subject->semester_id;

            // 🎯 DEGREE: choose ONLY 2-3 weekdays
            if ($level == 'degree' && !isset($weekdayUsage[$weekdayKey])) {
                $weekdayDays = $days->filter(function($d){
                    return !in_array(strtolower($d->day_name), ['saturday','sunday']);
                })->pluck('id')->toArray();

                shuffle($weekdayDays);
                $weekdayUsage[$weekdayKey] = array_slice($weekdayDays, 0, rand(2,3));
            }

            $slotsNeeded = [2,1]; // total = 3

            foreach ($slotsNeeded as $count) {

                $placed = false;

                foreach ($days as $day) {

                    $dayName = strtolower($day->day_name);
                    $isWeekend = in_array($dayName, ['saturday','sunday']);

                    // 🎯 DEGREE RULES
                    if ($level == 'degree') {

                        $allowedDays = $weekdayUsage[$weekdayKey];

                        // weekday must be selected days only
                        if (!$isWeekend && !in_array($day->id, $allowedDays)) {
                            continue;
                        }

                        // slots filter
                        $candidateSlots = $slots->filter(function($s) use ($isWeekend){
                            $hour = intval(explode(':',$s->start_time)[0]);

                            if ($isWeekend) {
                                return $hour >= 8; // weekend
                            } else {
                                return $hour >= 16; // weekday 16:00+
                            }
                        });

                    } else {
                        $candidateSlots = $slots;
                    }

                    $candidateSlots = $candidateSlots->values();

                    for ($i=0;$i<count($candidateSlots);$i++) {

                        if ($count == 2) {
                            if (!isset($candidateSlots[$i+1])) continue;
                            $targetSlots = [$candidateSlots[$i]->id,$candidateSlots[$i+1]->id];
                        } else {
                            $targetSlots = [$candidateSlots[$i]->id];
                        }

                        // 🚫 DAILY LIMIT (max 3)
                        $dailyKey = $weekdayKey.'_'.$day->id;
                        $currentCount = $dailyCount[$dailyKey] ?? 0;

                        if ($currentCount + $count > 3 && $level == 'degree' && !$isWeekend) {
                            continue;
                        }

                        // 🎯 ROOM: MUST USE course_rooms
                        $courseRoomIds = DB::table('course_rooms')
                            ->where('course_id',$subject->course_id)
                            ->where('nta_level',$subject->nta_level)
                            ->pluck('room_id')->toArray();

                        if (empty($courseRoomIds)) continue;

                        $rooms = DB::table('rooms')
                            ->whereIn('id',$courseRoomIds)
                            ->inRandomOrder()
                            ->get();

                        foreach ($rooms as $room) {

                            // ROOM conflict
                            $roomBusy = DB::table('timetables')
                                ->where('day_id',$day->id)
                                ->whereIn('timeslot_id',$targetSlots)
                                ->where('room_id',$room->id)
                                ->exists();

                            if ($roomBusy) continue;

                            // TEACHER conflict
                            $teacherConflict = false;
                            foreach ($groupSubjects as $gs) {
                                $busy = DB::table('timetables')
                                    ->where('day_id',$day->id)
                                    ->whereIn('timeslot_id',$targetSlots)
                                    ->where('teacher_id',$gs->teacher_id)
                                    ->exists();

                                if ($busy) {
                                    $teacherConflict = true;
                                    break;
                                }
                            }

                            if ($teacherConflict) continue;

                            // ✅ INSERT
                            foreach ($groupSubjects as $gs) {
                                foreach ($targetSlots as $ts) {
                                    DB::table('timetables')->insert([
                                        'subject_id'=>$gs->id,
                                        'teacher_id'=>$gs->teacher_id,
                                        'day_id'=>$day->id,
                                        'timeslot_id'=>$ts,
                                        'room_id'=>$room->id,
                                        'created_at'=>now(),
                                        'updated_at'=>now()
                                    ]);
                                }
                            }

                            // update counters
                            $dailyCount[$dailyKey] = $currentCount + $count;

                            $placed = true;
                            $conflictFound = true;
                            break 3;
                        }
                    }
                }

                if (!$placed) {
                    Log::warning("Failed placing subject group ".$groupName);
                }
            }
        }

        if (!$conflictFound) break;
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

    return [
        // get subjects for ALL active semesters
        'subjects' => DB::table('subjects')
            ->whereIn('semester_id', $semesterIds)
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'teachers' => DB::table('teachers')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray(),

        'rooms' => DB::table('rooms')
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

        $subjectIds = DB::table('subjects')->where('semester_id', $activeSemester->id)->pluck('id')->toArray();
        if (!empty($subjectIds)) {
            DB::table('timetables')->whereIn('subject_id', $subjectIds)->delete();
        }

        foreach ($timetableData as $entry) {
            $teacherId = DB::table('subjects')->where('id', $entry['subject_id'])->value('teacher_id');

            DB::table('timetables')->insert([
                'day_id' => $entry['day_id'],
                'subject_id' => $entry['subject_id'],
                'timeslot_id' => $entry['timeslot_id'],
                'room_id' => $entry['room_id'],
                'teacher_id' => $teacherId,
                'semester_id' => $activeSemester->id,
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
    $activeSemesters = DB::table('semesters')
        ->where('status', 'Active')
        ->orderBy('id')
        ->get();

    if ($activeSemesters->isEmpty()) {
        return redirect()->back()->with('error', 'No active semesters found');
    }

    $filterCourse = $request->input('course');
    $filterNta = $request->input('nta');

    $courses = DB::table('courses')->get();

    $ntaLevels = DB::table('subjects')
        ->distinct()
        ->pluck('nta_level');

    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
        ->join('teachers', 'subjects.teacher_id', '=', 'teachers.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
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
            'subjects.nta_level',
            'subjects.credit_hour',
            'timetables.group_name',
            'courses.courseName',
            'courses.short_name',
            'teachers.firstname',
            'teachers.lastname',
            'rooms.name as room_name',
            'semesters.semName as semester_name',
            'semesters.id as semester_id'
        )
        ->orderBy('semesters.id') // muhimu sana
        ->orderBy('courses.courseName')
        ->orderBy('subjects.nta_level')
        ->orderByRaw("FIELD(days.day_name,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
        ->orderBy('timeslots.start_time')
        ->get();

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
        'activeSemesters'
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
 


public function validateTimetable()
{
    $reports = [];

    // ==========================
    // GET ACTIVE SEMESTERS
    // ==========================
    $activeSemesters = DB::table('semesters')
        ->where('status','Active')
        ->pluck('id');

    $total_entries = DB::table('timetables')
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
    SELECT te.firstname, te.lastname, d.day_name, ti.start_time, ti.end_time, COUNT(*) as total
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
    AND s1.teacher_id = s2.teacher_id

    -- ONLY check subjects without group_name
    AND (s1.group_name IS NULL OR s1.group_name = '')
    AND (s2.group_name IS NULL OR s2.group_name = '')

    GROUP BY s1.teacher_id, te.firstname, te.lastname, d.day_name, ti.start_time, ti.end_time
    HAVING COUNT(*) > 1
");

    // ==========================
    // 4. NTA DOUBLE BOOKING (SHARED SESSION FIXED)
    // ==========================
    $reports['nta_double_booking'] = DB::select("
        SELECT c.courseName, d.day_name, ts.start_time, ts.end_time, s.nta_level, COUNT(*) AS total
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
        WHERE t.semester_id IN ($semesters)
        AND s.course_id = s2.course_id
        AND s.nta_level = s2.nta_level
        AND NOT (t.group_name IS NOT NULL AND t.group_name = t2.group_name)
        GROUP BY c.courseName, d.day_name, ts.start_time, ts.end_time, s.nta_level
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
            d.day_name,
            COUNT(*) as total
        FROM timetables t
        JOIN subjects s ON t.subject_id = s.id
        JOIN courses c ON s.course_id = c.id
        JOIN days d ON t.day_id = d.id
        WHERE t.semester_id IN ($semesters)
        GROUP BY c.courseName, s.course_id, s.nta_level, d.day_name
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

    return view('timetable.validation', compact('reports','score','total_entries','violations'));
}
public function showedit($id){
    $timetable=Timetable::findOrFail($id);
    $subjects = Subject::all();
    $rooms = Room::all();
    $teachers = Teacher::where("user_level","teacher")->get();
    $days = Day::all();
    $time = Timeslot::all();
    return view("timetableEdit", compact('timetable','subjects','rooms','days','time'));
}
public function update(Request $request,$id){
    $request->validate([
        "teacher_id" => "required",
        "room_id" => "required",
        "day_id" => "required",
        "timeslot_id" => "required"
    ]);
    $timetable = Timetable::findOrFail($id);
    $timetable->update($request->all());
    return redirect()->route("timetable.generate");
    
}
public function checkConflicts(Request $request)
{
    $timetableId = $request->input('timetable_id');
    $subjectId   = $request->input('subject_id');
    $roomId      = $request->input('room_id');
    $dayId       = $request->input('day_id');
    $timeslotId  = $request->input('timeslot_id');

    $conflicts = [];

    // ==========================
    // GET SUBJECT (WITH SEMESTER)
    // ==========================
    $subject = DB::table('subjects')->where('id', $subjectId)->first();

    if (!$subject) {
        return response()->json(['conflicts' => ['Subject not found.']]);
    }

    $semesterId = $subject->semester_id; // 🔥 muhimu sana

    $room = DB::table('rooms')->where('id', $roomId)->first();
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
    | SUBJECT SAME DAY
    |------------------------------------------------------------------ */

    $subjectConflict = DB::table('timetables')
        ->where('semester_id', $semesterId) // 🔥 FIX
        ->where('subject_id', $subjectId)
        ->where('day_id', $dayId)
        ->where('id', '!=', $timetableId)
        ->where('group_name', $groupName)
        ->count();

    if ($subjectConflict > 1) {
        $conflicts[] = "This subject is already scheduled today for this group.";
    }

    /* |------------------------------------------------------------------
    | ROOM DOUBLE BOOKING
    |------------------------------------------------------------------ */

    $roomConflict = DB::table('timetables')
        ->where('semester_id', $semesterId) // 🔥 FIX
        ->where('room_id', $roomId)
        ->where('day_id', $dayId)
        ->where('timeslot_id', $timeslotId)
        ->where('id', '!=', $timetableId)
        ->count();

    if ($roomConflict > 0) {
        $conflicts[] = "This room is already in use at this time.";
    }

    /* |------------------------------------------------------------------
    | TEACHER DOUBLE BOOKING
    |------------------------------------------------------------------ */

    $teacherConflict = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('t.semester_id', $semesterId) // 🔥 FIX
        ->where('s.teacher_id', $subject->teacher_id)
        ->where('t.day_id', $dayId)
        ->where('t.timeslot_id', $timeslotId)
        ->where('t.id', '!=', $timetableId)
        ->count();

    if ($teacherConflict > 0) {
        $conflicts[] = "Teacher is already teaching at this time.";
    }

    /* |------------------------------------------------------------------
    | NTA DOUBLE BOOKING (WITH SEMESTER)
    |------------------------------------------------------------------ */

    $ntaConflict = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('t.semester_id', $semesterId) // 🔥 muhimu sana
        ->where('s.course_id', $subject->course_id)
        ->where('s.nta_level', $subject->nta_level)
        ->where('s.semester_id', $semesterId) // 🔥 FIX
        ->where('t.day_id', $dayId)
        ->where('t.timeslot_id', $timeslotId)
        ->where('t.group_name', $groupName)
        ->where('t.id', '!=', $timetableId)
        ->count();

    if ($ntaConflict > 0) {
        $conflicts[] = "This NTA group already has a class at this time.";
    }

    /* |------------------------------------------------------------------
    | NTA DAILY LIMIT
    |------------------------------------------------------------------ */

    $ntaLessons = DB::table('timetables AS t')
        ->join('subjects AS s', 't.subject_id', '=', 's.id')
        ->where('t.semester_id', $semesterId) // 🔥 FIX
        ->where('s.course_id', $subject->course_id)
        ->where('s.nta_level', $subject->nta_level)
        ->where('s.semester_id', $semesterId) // 🔥 FIX
        ->where('t.day_id', $dayId)
        ->where('t.group_name', $groupName)
        ->count();

    if ($ntaLessons >= 10) {
        $conflicts[] = "This NTA group has exceeded 10 classes today.";
    }

    /* |------------------------------------------------------------------
    | CREDIT HOURS
    |------------------------------------------------------------------ */

    $subjectLessons = DB::table('timetables')
        ->where('semester_id', $semesterId) // 🔥 FIX
        ->where('subject_id', $subjectId)
        ->where('group_name', $groupName)
        ->count();

    if ($subjectLessons > $subject->credit_hour) {
        $conflicts[] = "This subject has exceeded its credit hours for this group.";
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

    $subject = DB::table('subjects')->where('id', $subjectId)->first();
    if (!$subject) {
        return response()->json(['solutions' => []]);
    }

    $days  = DB::table('days')->get();
    $slots = DB::table('timeslots')->get();

    // ==========================
    // 1️⃣ ROOM FILTERING
    // ==========================

    if (strtolower($subject->required_lab) !== 'theory') {
        $rooms = DB::table('rooms')
            ->leftJoin('buildings','rooms.building_id','=','buildings.id')
            ->where('rooms.type', 'Lab')
            ->where('rooms.practical_type', $subject->required_lab)
            ->select('rooms.*','buildings.building_name')
            ->get();
    } else {
        $rooms = DB::table('rooms')
            ->leftJoin('buildings','rooms.building_id','=','buildings.id')
            ->where('rooms.type', 'Normal')
            ->select('rooms.*','buildings.building_name')
            ->get();
    }

    if ($rooms->count() == 0) {
        return response()->json(['solutions' => []]);
    }

    $solutions = [];

    // ==========================
    // 2️⃣ LOOP DAYS
    // ==========================

    foreach ($days as $day) {

        $daySolutionsCount = 0; // limit ya solutions kwa siku (max 5)

        foreach ($slots as $slot) {
            foreach ($rooms as $room) {

                // limit ya solutions kwa siku moja
                if ($daySolutionsCount >= 5) {
                    break;
                }

                // ==========================
                // CONFLICT CHECK (UPDATED)
                // ==========================

                $hasConflict = DB::table('timetables AS t')
                    ->join('subjects AS s', 't.subject_id', '=', 's.id')
                    ->where(function ($q) use ($subject, $day, $slot, $room) {

                        // ROOM conflict
                        $q->orWhere(function ($q2) use ($day, $slot, $room) {
                            $q2->where('t.room_id', $room->id)
                               ->where('t.day_id', $day->id)
                               ->where('t.timeslot_id', $slot->id);
                        });

                        // TEACHER conflict
                        $q->orWhere(function ($q2) use ($subject, $day, $slot) {
                            $q2->where('s.teacher_id', $subject->teacher_id)
                               ->where('t.day_id', $day->id)
                               ->where('t.timeslot_id', $slot->id);
                        });

                        // COURSE + NTA + SEMESTER conflict (🔥 FIXED)
                        $q->orWhere(function ($q2) use ($subject, $day, $slot) {
                            $q2->where('s.course_id', $subject->course_id)
                               ->where('s.nta_level', $subject->nta_level)
                               ->where('s.semester_id', $subject->semester_id) // 🔥 muhimu sana
                               ->where('t.day_id', $day->id)
                               ->where('t.timeslot_id', $slot->id);
                        });

                    })
                    ->where('t.id', '!=', $timetableId)
                    ->count();

                if ($hasConflict == 0) {

                    $roomLabel = $room->name;
                    if ($room->building_name) {
                        $roomLabel .= " (" . $room->building_name . ")";
                    }

                    $solutions[] = [
                        'day_id'    => $day->id,
                        'day_name'  => $day->day_name,
                        'slot_id'   => $slot->id,
                        'slot_time' => $slot->start_time . " - " . $slot->end_time,
                        'room_id'   => $room->id,
                        'room_name' => $roomLabel
                    ];

                    $daySolutionsCount++; // ongeza counter
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
    // ✅ 1) Check teacher
    $teacher = DB::table('teachers')->where('id', $id)->first();
    if (!$teacher) {
        return redirect()->back()->with('error', 'Teacher not found');
    }

    // ✅ 2) Active semester
    $activeSemester = DB::table('semesters')->where('status', 'Active')->first();

    // ✅ 3) Fetch timetable entries + group
    $entries = DB::table('timetables')
        ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
        ->join('courses', 'subjects.course_id', '=', 'courses.id')
        ->join('days', 'timetables.day_id', '=', 'days.id')
        ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
        ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
        ->where('subjects.teacher_id', $id)
        ->when($activeSemester, fn($q) => $q->where('subjects.semester_id', $activeSemester->id))
        ->select(
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'subjects.subjectName',
            'courses.courseName',
            'subjects.nta_level',
            'timetables.group_name',   // ✅ used in display
            'rooms.name as room_name'
        )
        ->orderBy('days.id')
        ->orderBy('timeslots.start_time')
        ->orderBy('timetables.group_name')
        ->get();

    // ✅ Group by day only (not group)
    $groupedEntries = $entries->groupBy('day_name');

    return view('viewttimetable', compact('teacher', 'groupedEntries', 'activeSemester'));
}


public function solveConflicts(Request $request)
{
    // Increase execution limits
    ini_set('max_execution_time', 300);
    set_time_limit(300);
    ignore_user_abort(true);
    ini_set('memory_limit', '1024M');

    // Get active semester
    $semester = DB::table('semesters')
        ->where('status', 'Active')
        ->first();

    $subjects = DB::table('subjects')->get();
    $days     = DB::table('days')->get();
    $slots    = DB::table('timeslots')->get();
    $rooms    = DB::table('rooms')->get();

    foreach ($subjects as $subject) {

        // Count already scheduled periods
        $scheduled = DB::table('timetables')
            ->where('semester_id', $semester->id)
            ->where('subject_id', $subject->id)
            ->count();

        $remaining = $subject->credit_hour - $scheduled;

        if ($remaining <= 0) {
            continue;
        }

        for ($i = 0; $i < $remaining; $i++) {

            $placed = false;

            foreach ($days as $day) {
                foreach ($slots as $slot) {
                    foreach ($rooms as $room) {

                        /* ================= ROOM TYPE CHECK ================= */
                        $subjectType = strtolower(trim($subject->subject_type));
                        $roomType    = strtolower(trim($room->type));

                        // Practical lazima iwe Lab
                        if ($subjectType === 'practical' && $roomType !== 'lab') {
                            continue;
                        }

                        // Theory isiwe Lab
                        if ($subjectType === 'theory' && $roomType === 'lab') {
                            continue;
                        }

                        /* ================= ROOM CONFLICT ================= */
                        $roomBusy = DB::table('timetables')
                            ->where('semester_id', $semester->id)
                            ->where('day_id', $day->id)
                            ->where('timeslot_id', $slot->id)
                            ->where('room_id', $room->id)
                            ->exists();

                        if ($roomBusy) {
                            continue;
                        }

                        /* ================= TEACHER CONFLICT ================= */
                        $teacherBusy = DB::table('timetables as t')
                            ->join('subjects as s', 't.subject_id', '=', 's.id')
                            ->where('t.semester_id', $semester->id)
                            ->where('s.teacher_id', $subject->teacher_id)
                            ->where('t.day_id', $day->id)
                            ->where('t.timeslot_id', $slot->id)
                            ->exists();

                        if ($teacherBusy) {
                            continue;
                        }

                        /* ================= GROUP CONFLICT ================= */
                        $groupBusy = DB::table('timetables as t')
                            ->join('subjects as s', 't.subject_id', '=', 's.id')
                            ->where('t.semester_id', $semester->id)
                            ->where('s.course_id', $subject->course_id)
                            ->where('s.nta_level', $subject->nta_level)
                            ->where('t.group_name', $subject->group_name)
                            ->where('t.day_id', $day->id)
                            ->where('t.timeslot_id', $slot->id)
                            ->exists();

                        if ($groupBusy) {
                            continue;
                        }

                        /* ================= SUBJECT SAME DAY ================= */
                        $subjectToday = DB::table('timetables')
                            ->where('semester_id', $semester->id)
                            ->where('subject_id', $subject->id)
                            ->where('day_id', $day->id)
                            ->count();

                        if ($subjectToday >= 1) {
                            continue;
                        }

                        /* ================= INSERT LESSON ================= */
                        DB::table('timetables')->insert([
                            'semester_id' => $semester->id,
                            'subject_id'  => $subject->id,
                            'room_id'     => $room->id,
                            'day_id'      => $day->id,
                            'timeslot_id' => $slot->id,
                            'group_name'  => $subject->group_name,
                            'created_at'  => now(),
                            'updated_at'  => now()
                        ]);

                        $placed = true;

                        break 3; // kutoka kwenye loops zote
                    }
                }
            }

            if (!$placed) {
                echo "Imeshindikana kupanga subject: ";
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
    ini_set('memory_limit', '1024M');

    echo "<div style='padding:15px; background:#d1ffd1; border-left:5px solid #28a745;'>
            Conflict solving started... please wait. You can continue using the system.
          </div>";
    ob_flush();
    flush();

    $hasChanges = true;

    while ($hasChanges) {

        $hasChanges = false;

        $timetables = DB::table('timetables')->orderBy('id')->get();

        foreach ($timetables as $timetable) {

            $subject = DB::table('subjects')
                ->where('id', $timetable->subject_id)
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
                        ->whereRaw('LOWER(practical_type) = ?', [strtolower($subject->required_lab)])
                        ->get();

                    foreach ($labs as $lab) {

                        $roomConflict = DB::table('timetables')
                            ->where('room_id', $lab->id)
                            ->where('day_id', $timetable->day_id)
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



public function reduceEveningSessions(Request $request)
{
    // Epuka timeout
    set_time_limit(300);

    // 🔹 Pata semester yenye status Active
    $activeSemester = DB::table('semesters')
        ->where('status', 'Active')
        ->first();

    if (!$activeSemester) {
        return redirect()->back()->with('error', 'No active semester found.');
    }

    // 🔹 Pata vipindi vya jioni vya semester husika (mfano 16:00 - 18:30)
    $eveningLessons = DB::table('timetables as t')
        ->join('subjects as s', 't.subject_id', '=', 's.id')
        ->join('timeslots as ts', 't.timeslot_id', '=', 'ts.id')
        ->where('t.semester_id', $activeSemester->id)
        ->where('ts.start_time', '>=', '16:00:00')
        ->where('ts.end_time', '<=', '18:30:00')
        ->select('t.*', 's.nta_level', 's.course_id', 's.teacher_id')
        ->get()
        ->groupBy('nta_level');

    $movedCount = 0;

    foreach ($eveningLessons as $ntaLevel => $lessons) {
        // Acha kipindi kimoja tu kibaki
        $keepOne = true;

        foreach ($lessons as $lesson) {
            if ($keepOne) {
                $keepOne = false; // acha hiki kimoja
                continue;
            }

            // 🔹 Tafuta solutions zinazowezekana kwa kipindi hiki
            $solutionsResponse = $this->checkSolutions(new Request([
                'timetable_id' => $lesson->id,
                'subject_id'   => $lesson->subject_id
            ]));

            $solutions = $solutionsResponse->getData(true)['solutions'] ?? [];

            if (count($solutions) > 0) {
                // 🔸 Chagua solution ambayo siyo kipindi cha jioni
                $newSlot = collect($solutions)->first(function ($sol) {
                    // Hapa tunahakikisha muda unaanza kabla ya 16:00
                    [$start] = explode(' - ', $sol['slot_time']);
                    return strtotime($start) < strtotime('16:00');
                });

                if ($newSlot) {
                    DB::table('timetables')
                        ->where('id', $lesson->id)
                        ->where('semester_id', $activeSemester->id)
                        ->update([
                            'day_id'      => $newSlot['day_id'],
                            'timeslot_id' => $newSlot['slot_id'],
                            'room_id'     => $newSlot['room_id'],
                            'updated_at'  => now()
                        ]);

                    $movedCount++;
                }
            }
        }
    }

    return redirect()->back()
        ->with('success', "Evening sessions reduced successfully. {$movedCount} lessons moved for the active semester.");
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

    $groups = Subject::whereNotNull('group_name')
        ->get()
        ->groupBy('group_name');

    foreach ($groups as $groupName => $subjects) {

        $referenceSubject = $subjects->first();

        $referenceTimetable = DB::table('timetables')
            ->where('subject_id', $referenceSubject->id)
            ->orderBy('timeslot_id')
            ->get();

        if ($referenceTimetable->count() == 0) {
            continue;
        }

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
                        'room_id' => $referenceTimetable[$index]->room_id,
                    ]);

            }

        }

    }

    return back()->with('success','Grouped subjects updated successfully');

}


    // public function exportAll()
    // {
    //     return Excel::download(new TimetableExport, 'timetable.xlsx');
    // }
   























}