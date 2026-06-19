<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable =[
        "name",
        "capacity",
        "type",
        "status",
        "practical_type",
        "branch_id"
    ];
     public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
   
}
