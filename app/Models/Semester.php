<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        "semName",
        "academic_year",
        "start_date",
        "end_date",
        "semCode",
    ];
    public function subjects(){
        return $this->hasMany(Semester::class,"semester_id");
    }
}
