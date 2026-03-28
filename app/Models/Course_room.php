<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_room extends Model
{
    protected $fillable = ['course_id', 'nta_level', 'group_name', 'room_id'];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
