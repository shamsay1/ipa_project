<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class DepartmentTimetableSheet implements FromView, WithTitle
{
    protected $department;
    protected $type; // degree / diploma

    public function __construct(Department $department, string $type)
    {
        $this->department = $department;
        $this->type = $type;
    }

    public function view(): View
    {
        $query = DB::table('timetables')
            ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
            ->join('courses', 'subjects.course_id', '=', 'courses.id')
            ->join('departments', 'courses.deptId', '=', 'departments.id')
            ->join('days', 'timetables.day_id', '=', 'days.id')
            ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
            ->join('rooms', 'timetables.room_id', '=', 'rooms.id')
            ->join('semesters', 'subjects.semester_id', '=', 'semesters.id')
            ->where('departments.id', $this->department->id)
            ->where('semesters.status', 'Active');

        // Filter kulingana na type
        if ($this->type === 'Degree') {
            $query->where('subjects.nta_level', '>', 'NTA-6');
        } elseif ($this->type === 'Diploma') {
            $query->whereIn('subjects.nta_level', ['NTA-4', 'NTA-5','NTA-6']);
        }

        $timetable = $query->select(
            'days.day_name',
            'timeslots.start_time',
            'timeslots.end_time',
            'courses.courseName',
            'subjects.nta_level',
            'subjects.subjectName',
            'rooms.name as room',
            'timetables.group_name'
        )
        ->orderByRaw("FIELD(subjects.nta_level, 'NTA-4','NTA-5','NTA-6')")
        ->orderBy('courses.courseName')
        ->orderBy('timetables.group_name')
        ->orderBy('days.id')
        ->orderBy('timeslots.start_time')
        ->get();

        return view('exports.department_timetable', [
            'department' => $this->department,
            'timetable' => $timetable
        ]);
    }

    public function title(): string
    {
        return $this->department->deptName;
    }
}
