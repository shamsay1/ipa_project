<?php

namespace App\Imports;

use App\Models\Building;
use App\Models\Course;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;

class CourseImport implements ToModel, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    private $courseCodes = [];

    

    public function model(array $row)
    {
    $dept = Department::where("dept_code", $row['dept_code'])->first();
    return new Course([
        "courseName"  => $row['course_name'],
        "course_code" => $row['course_code'],
        "short_name" => $row['short_name'],
        "deptId"      => $dept ? $dept->id : null,
        "course_level" => $row["course_level"],
    ]);
    }

    public function rules(): array
    {
        return [
            '*.course_name' => 'required|string|max:100',
            '*.course_code' => 'required|string|max:50',
            '*.dept_code'   => 'required|exists:departments,dept_code',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.course_name.required' => 'Course name is required',
            '*.course_code.required' => 'Course code is required',
            '*.dept_code.required'   => 'Department code is required',
            '*.dept_code.exists'     => 'Department does not exist',
        ];
    }
}
