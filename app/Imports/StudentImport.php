<?php

namespace App\Imports;

use App\Models\CrInfo;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentImport implements ToModel, WithHeadingRow, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

   public function model(array $row)
{
    // kama row ni empty iruke
    if (!$row['firstname']) {
        return null;
    }

    // tafuta course
    $course = Course::where('courseName', $row['course_name'])->first();

    // tafuta semester
    $semester = Semester::where('semName', $row['semester_name'])
                        ->where('status','Active')
                        ->first();

    return new CrInfo([
        'firstname'   => $row['firstname'],
        'middlename'  => $row['middlename'],
        'lastname'    => $row['lastname'],
        'email'       => $row['email'],
        'mobile'      => $row['mobile'],
        'password' => trim($row['password']),
        'course_id'   => $course ? $course->id : null,
        'semester_id' => $semester ? $semester->id : null,
        'nta'         => $row['nta_level'],
        "branch_id"    => $this->branchId,

    ]);
}
}