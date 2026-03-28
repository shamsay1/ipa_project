<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        "day_id",
        "subject_id",
        "timeslot_id",
        "room_id",
        "semester_id"
    ];
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
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
