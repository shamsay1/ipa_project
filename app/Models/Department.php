<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable =[
        "deptName",
        "dept_code",
    ];

    public function courses(){
        return $this->hasMany(Course::class,"deptId");
    }
    public function teachers(){
        return $this->hasMany(Teacher::class,"deptId");
    }
}
