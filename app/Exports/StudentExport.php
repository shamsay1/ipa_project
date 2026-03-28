<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            "firstname",
            "middlename",
            "lastname",
            "email",
            "mobile",
            "password",
            "course_name",
            "nta_level",
            "semester_name"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | COURSE DROPDOWN
                |--------------------------------------------------------------------------
                */

                $courses = Course::pluck('courseName')->toArray();

                $row = 1;
                foreach ($courses as $course) {
                    $sheet->setCellValue("J{$row}", $course);
                    $row++;
                }

                $courseRange = '$J$1:$J$' . count($courses);

                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet->getCell("G{$row}")->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid Course');
                    $validation->setError('Please select course from dropdown.');
                    $validation->setPromptTitle('Select Course');
                    $validation->setPrompt('Choose course from list');
                    $validation->setFormula1($courseRange);
                }

                /*
                |--------------------------------------------------------------------------
                | NTA LEVEL DROPDOWN
                |--------------------------------------------------------------------------
                */

                $ntaLevels = '"NTA-4,NTA-5,NTA-6,NTA-7,NTA-8"';

                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet->getCell("H{$row}")->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid NTA Level');
                    $validation->setError('Select NTA Level from list.');
                    $validation->setPromptTitle('Select NTA Level');
                    $validation->setPrompt('Choose NTA Level');
                    $validation->setFormula1($ntaLevels);
                }

                /*
                |--------------------------------------------------------------------------
                | SEMESTER DROPDOWN (ONLY ACTIVE)
                |--------------------------------------------------------------------------
                */

                $semesters = Semester::where('status','Active')
                                ->pluck('semName')
                                ->toArray();

                $row = 1;
                foreach ($semesters as $semester) {
                    $sheet->setCellValue("K{$row}", $semester);
                    $row++;
                }

                $semesterRange = '$K$1:$K$' . count($semesters);

                for ($row = 2; $row <= 1000; $row++) {

                    $validation = $sheet->getCell("I{$row}")->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid Semester');
                    $validation->setError('Please select semester from dropdown.');
                    $validation->setPromptTitle('Select Semester');
                    $validation->setPrompt('Choose semester from list');
                    $validation->setFormula1($semesterRange);
                }

                /*
                |--------------------------------------------------------------------------
                | STYLING
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:I1')->getFont()->setBold(true);

                foreach (['A','B','C','D','E','F','G','H','I'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                /*
                |--------------------------------------------------------------------------
                | HIDE SUPPORT COLUMNS
                |--------------------------------------------------------------------------
                */

                $sheet->getColumnDimension('J')->setVisible(false);
                $sheet->getColumnDimension('K')->setVisible(false);
            },
        ];
    }
}