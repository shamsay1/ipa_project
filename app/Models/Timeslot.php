<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    protected $fillable = [
        "start_time",
        "end_time",
    ];
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
