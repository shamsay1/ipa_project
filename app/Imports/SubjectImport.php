<?php

namespace App\Imports;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubjectImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $subjectCodes = [];

    // public function collection(Collection $rows)
    // {
        
    //     foreach ($rows as $index => $row) {
    //         $code = trim($row['subjectcode']);
    //         if (in_array($code, $this->subjectCodes)) {
    //             $failure = new \Maatwebsite\Excel\Validators\Failure(
    //                 $index + 2, // +2 for heading row
    //                 'subjectcode',
    //                 ['Duplicate subject code in file']
    //             );
    //             $this->onFailure($failure);
    //         } else {
    //             $this->subjectCodes[] = $code;
    //         }
    //     }
    // }

    public function model(array $row)
{
   
    $teacher = Teacher::whereRaw(
        "CONCAT(firstname,' ',middlename,' ',lastname) = ?", 
        [$row['teacher_name']]   // Excel column = teacher_name
    )->first();
    $course  = Course::where("course_code", $row['course_code'])->first();
    $semester = Semester::where("semCode", $row['sem_code'])->first();

    return new Subject([
        "course_id"     => $course ? $course->id : null,
        "subjectName"   => $row['subjectname'],
        "subjectCode"   => $row['subjectcode'],
        "teacher_id"    => $teacher ? $teacher->id : null,
        "nta_level"     => $row['nta_level'],
        "semester_id"   => $semester ? $semester->id : null,
        "subject_type"  => $row['subject_type'],
        "required_lab"  => $row['required_lab'],
        "credit_hour"   => $row['credit_hour'],
        "group_name"    => $row['group_name'],
    ]);
}

    public function rules(): array
    {
        return [
            '*.subjectname'   => 'required|string|max:100',
            '*.subjectcode'   => 'required|string|max:50',
            '*.course_code'   => 'required|exists:courses,course_code',
            '*.nta_level'     => 'required|string|max:50',
            '*.sem_code'      => 'required|exists:semesters,semCode',
            '*.subject_type'  => 'required|string|max:50',
            '*.required_lab'  => 'nullable|string|max:50',
            '*.credit_hour'   => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.subjectname.required'  => 'Subject name is required',
            '*.subjectcode.required'  => 'Subject code is required',
            '*.course_code.required'  => 'Course code is required',
            '*.course_code.exists'    => 'Course does not exist',
            '*.nta_level.required'    => 'NTA level is required',
            '*.sem_code.required'     => 'Semester code is required',
            '*.sem_code.exists'       => 'Semester does not exist',
            '*.subject_type.required' => 'Subject type is required',
            '*.credit_hour.required'  => 'Credit hour is required',
            '*.credit_hour.numeric'   => 'Credit hour must be a number',
        ];
    }
}
