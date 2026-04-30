<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
        "firstname",
        "middlename",
        "lastname",
        "gender",
        "mobile",
        "email",
        "password",
        "teacher_code",
        "deptId",
        "role",
        "branch_id"

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guard = 'teacher';
    public function subjects(){
        return $this->hasMany(Subject::class,"subject_id");
    }
    public function department(){
        return $this->belongsTo(Department::class,"deptId");
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
