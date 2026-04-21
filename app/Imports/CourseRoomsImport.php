<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Course_room;
use App\Models\Room;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CourseRoomsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
  public function model(array $row)
{
    $courseText = $row['coursename'] ?? null;

    if (!$courseText) return null;

    
    $parts = explode('-', $courseText);

    if (count($parts) < 2) return null;

    $shortName = trim($parts[0]); 
    $level     = trim($parts[1]); 

    $course = Course::where('short_name', $shortName)
        ->where('course_level', $level)
        ->first();

    $room = Room::where('name', $row['class_name'])->first();

    if (!$course || !$room) {
        return null;
    }

    return new Course_room([
        'course_id' => $course->id,
        'nta_level' => $row['nta_level'],
        'room_id'   => $room->id,
        'total_students' =>trim($row['total_students']),
    ]);
}
}
