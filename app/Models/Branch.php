<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class Branch extends Model
{
    protected $fillable = [
        "branch_name",
    ];
     public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(CrInfo::class);
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    public function courseRooms()
{
    return $this->hasMany(Course_room::class);
}
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
