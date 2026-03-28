<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable =[
        "subjectName",
        "subjectCode",
        "teacher_id",
        "course_id",
        "nta_level",
        "group_name",
        "semester_id",
        "subject_type",
        "required_lab",
        "credit_hour",
    ];
    public function teacher(){
        return $this->belongsTo(Teacher::class,"teacher_id");
    }
    public function semester(){
        return $this->belongsTo(Semester::class,"semester_id");
    }
    public function course(){
        return $this->belongsTo(Course::class,"course_id");
    }
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

}
