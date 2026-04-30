<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures; 
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }
    public function model(array $row)
    {
        $dept = Department::where("dept_code", $row['dept_code'])->first();
        
        return new Teacher([
            "firstname"    => strtoupper($row["firstname"]),
            "middlename"     => strtoupper($row["middlename"]),
            "lastname"     => strtoupper($row["lastname"]),
            "gender"       => $row["gender"],
            "mobile"       => $row["mobile"],
            "email"        => $row["email"],
            'password'     => Hash::make(trim($row['password'])),
            "teacher_code" => $row['teacher_code'],
            "deptId"       => $dept ? $dept->id : null, 
            "branch_id"    => $this->branchId,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.firstname'    => 'required|string|max:50',
            '*.lastname'     => 'required|string|max:50',
            '*.gender'       => 'required|string|max:50',
            '*.mobile'       => 'required|string|max:50',
            '*.email'        => 'required|email|unique:teachers,email',
            '*.password'     => 'required|max:50',
            '*.dept_code'    => 'required|exists:departments,dept_code', // Ensure department exists
            '*.teacher_code' => 'required|string|max:50',
        ];
    }

    // Optional: customize validation messages
    public function customValidationMessages()
    {
        return [
            '*.firstname.required' => 'Firstname is required',
            '*.lastname.required'  => 'Lastname is required',
            '*.gender.required'    => 'Gender is required',
            '*.mobile.required'    => 'Mobile number is required',
            '*.email.required'     => 'Email is required',
            '*.email.email'        => 'Email must be valid',
            '*.email.unique'       => 'Email already exists',
            '*.password.required'  => 'Password is required',
            '*.dept_code.required' => 'Department code is required',
            '*.dept_code.exists'   => 'Department does not exist',
            '*.teacher_code.required' => 'Teacher code is required',
        ];
    }
}
