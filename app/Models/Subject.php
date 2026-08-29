<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
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
        "branch_id"
    ];
    protected static function booted()
    {
        static::addGlobalScope('branch', function ($query) {
            if (Auth::check()) {
                $query->where('branch_id', Auth::user()->branch_id);
            }
        });

        
        static::creating(function ($model) {
            if (Auth::check() && empty($model->branch_id)) {
                $model->branch_id = Auth::user()->branch_id;
            }
        });
    }
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
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function attendances() {
    return $this->hasMany(TeacherAttendance::class, 'subject_id');
}
   

}
