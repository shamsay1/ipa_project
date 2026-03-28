<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Course extends Authenticatable
{
    use Notifiable;

    protected $table = 'courses';

    protected $fillable = [
        "courseName",
        "course_code",
        "short_name",
        "deptId",
        "course_level",
        "building_id",
        "username",
        "password"
    ];

    protected $hidden = [
        "password",
        "remember_token"
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class, "deptId");
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, "course_id");
    }

    public function building()
    {
        return $this->belongsTo(Building::class, "building_id");
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, "course_id");
    }

    public function rooms()
    {
        return $this->belongsToMany(
            Course_room::class,
            'course_room',
            'course_id',
            'room_id'
        );
    }
}
