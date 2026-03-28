<?php

namespace App\Exports;

use App\Models\Subject;
use App\Models\Semester;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SubjectExportTemplate implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            "subjectname",
            "subjectcode",
            "course_code",
            "nta_level",     // dropdown (NTA-4, NTA-5, NTA-6)
            "sem_code",
            "teacher_name",
            "subject_type",  // dropdown (Practical, Theory)
            "required_lab", // dropdown (Computer, Work shop, Mechanics, Civil lab)
            "credit_hour",
            "group_name"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $spreadsheet = $sheet->getParent();

                /*
                ================================
                1️⃣ TEACHER NAME DROPDOWN (COLUMN F)
                ================================
                */
                $teachers = Teacher::orderBy('firstname','asc')->get();

                // helper sheet
                $teacherSheet = $spreadsheet->createSheet();
                $teacherSheet->setTitle('Teachers');

                $row = 1;
                foreach ($teachers as $teacher) {
                    $fullname = $teacher->firstname . ' ' .$teacher->middlename.' '. $teacher->lastname;
                    $teacherSheet->setCellValue("A{$row}", $fullname);
                    $row++;
                }
                $lastRow = $row - 1;

                // dropdown validation
                $teacherValidation = new DataValidation();
                $teacherValidation->setType(DataValidation::TYPE_LIST);
                $teacherValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $teacherValidation->setAllowBlank(true);
                $teacherValidation->setShowInputMessage(true);
                $teacherValidation->setShowErrorMessage(true);
                $teacherValidation->setShowDropDown(true);
                $teacherValidation->setFormula1("=Teachers!\$A\$1:\$A\${$lastRow}");

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("F$i")->setDataValidation(clone $teacherValidation);
                }

                /*
                ================================
                2️⃣ SEMESTER DROPDOWN (COLUMN E)
                ================================
                */
                $activeSemesters = Semester::where('status', 'Active')
                    ->pluck('semCode')
                    ->toArray();

                $semesterList = '"' . implode(',', $activeSemesters) . '"';

                $semesterValidation = new DataValidation();
                $semesterValidation->setType(DataValidation::TYPE_LIST);
                $semesterValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $semesterValidation->setAllowBlank(false);
                $semesterValidation->setShowInputMessage(true);
                $semesterValidation->setShowErrorMessage(true);
                $semesterValidation->setShowDropDown(true);
                $semesterValidation->setFormula1($semesterList);

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("E$i")->setDataValidation(clone $semesterValidation);
                }

                /*
                ================================
                3️⃣ NTA LEVEL DROPDOWN (COLUMN D)
                ================================
                */
                $ntaValidation = new DataValidation();
                $ntaValidation->setType(DataValidation::TYPE_LIST);
                $ntaValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $ntaValidation->setAllowBlank(false);
                $ntaValidation->setShowInputMessage(true);
                $ntaValidation->setShowErrorMessage(true);
                $ntaValidation->setShowDropDown(true);
                $ntaValidation->setFormula1('"NTA-4,NTA-5,NTA-6,NTA-7,NTA-8,NTA-9,NTA-10,NTA-11"');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("D$i")->setDataValidation(clone $ntaValidation);
                }

                /*
                ================================
                4️⃣ SUBJECT TYPE DROPDOWN (COLUMN G)
                ================================
                */
                $subjectTypeValidation = new DataValidation();
                $subjectTypeValidation->setType(DataValidation::TYPE_LIST);
                $subjectTypeValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $subjectTypeValidation->setAllowBlank(false);
                $subjectTypeValidation->setShowInputMessage(true);
                $subjectTypeValidation->setShowErrorMessage(true);
                $subjectTypeValidation->setShowDropDown(true);
                $subjectTypeValidation->setFormula1('"Practical,Theory"');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("G$i")->setDataValidation(clone $subjectTypeValidation);
                }

                /*
                ================================
                5️⃣ REQUIRED LAB DROPDOWN (COLUMN H)
                ================================
                */
                $labValidation = new DataValidation();
                $labValidation->setType(DataValidation::TYPE_LIST);
                $labValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $labValidation->setAllowBlank(true);
                $labValidation->setShowInputMessage(true);
                $labValidation->setShowErrorMessage(true);
                $labValidation->setShowDropDown(true);
                $labValidation->setFormula1('"Theory,Computer Lab,Electronics Lab,Engineering Drawing Lab,Civil Materials Lab,Clinical Chemistry Lab,Biochemistry Lab,Microbiology Lab"');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("H$i")->setDataValidation(clone $labValidation);
                }

                // make headers bold and auto-size columns
                $sheet->getStyle('A1:J1')->getFont()->setBold(true);
                foreach (['A', 'B', 'C', 'D', 'E','F','G','H','I','J'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}