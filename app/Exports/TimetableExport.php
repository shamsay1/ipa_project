<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TimetableExport implements WithMultipleSheets
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type; // 'degree' or 'diploma'
    }

    public function sheets(): array
    {
        $sheets = [];
        $departments = Department::all();

        foreach ($departments as $dept) {
            $sheets[] = new DepartmentTimetableSheet($dept, $this->type);
        }

        return $sheets;
    }
}
