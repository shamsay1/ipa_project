<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLeave extends Model
{
     protected $fillable = [
        'teacher_id',
        'reason',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Relationship: Exception belongs to a teacher.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
