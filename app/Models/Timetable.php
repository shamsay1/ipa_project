<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Timetable extends Model
{

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
    protected $fillable = [
        "day_id",
        "subject_id",
        "timeslot_id",
        "room_id",
        "semester_id",
        "branch_id"
    ];
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class);
    }
    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_id');
}

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
}
