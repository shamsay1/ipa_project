<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class CourseExportTemplate implements FromCollection,WithHeadings
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
            "course_name",
            "course_code",
            "short_name",
            "dept_code",
            "course_level",
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1️⃣ NTA level column (D column = col 4)
                $ntaValidation = new DataValidation();
                $ntaValidation->setType(DataValidation::TYPE_LIST);
                $ntaValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $ntaValidation->setAllowBlank(false);
                $ntaValidation->setShowInputMessage(true);
                $ntaValidation->setShowErrorMessage(true);
                $ntaValidation->setShowDropDown(true);
                $ntaValidation->setFormula1('"Diploma,Degree"');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("D$i")->setDataValidation(clone $ntaValidation);
                }

                

                
            },
        ];
    }
}
