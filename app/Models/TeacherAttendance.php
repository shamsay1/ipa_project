<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'timetable_id',
        'date',
        'status',
        'status2',
        'course_id'
    ];
    public function course()
{
    return $this->belongsTo(Course::class);
}

 public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
