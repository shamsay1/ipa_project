<?php
namespace App\Imports;

use App\Models\Course;
use App\Models\Course_room;
use App\Models\Room;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CourseRoomsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    public function model(array $row)
    {
        
        $courseText = $row['coursename'] ?? $row['course_name'] ?? null;

        if (!$courseText) {
            Log::warning('Missing course name', $row);
            return null;
        }

        $parts = explode('-', $courseText);

        if (count($parts) < 2) {
            Log::warning('Invalid course format', $row);
            return null;
        }

        $course = Course::where('short_name', trim($parts[0]))
            ->where('course_level', trim($parts[1]))
            ->first();

        $room = Room::where('name', trim($row['class_name'] ?? ''))->first();

        if (!$course || !$room) {
            Log::warning('Course or Room not found', $row);
            return null;
        }

        return new Course_room([
            'course_id' => $course->id,
            'nta_level' => $row['nta_level'] ?? null,
            'room_id' => $room->id,
            'total_students' => $row['total_students'] ?? 0,
            'branch_id' => $this->branchId,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.coursename' => 'required',
            '*.class_name' => 'required',
            '*.nta_level' => 'required',
            '*.total_students' => 'required|numeric',
        ];
    }
}