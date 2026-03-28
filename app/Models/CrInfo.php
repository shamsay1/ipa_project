<?php

namespace App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CrInfo extends Authenticatable
{
    protected $table = 'cr_info'; 

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'mobile',
        'email',
        'password',
        'course_id',
        'semester_id',
        'nta',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Automatic password hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function semester()
{
    return $this->belongsTo(Semester::class);
}
}
