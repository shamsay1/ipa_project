<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TeacherTemplateExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([]);
    }
    
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            "firstname",
            "middlename",
            "lastname",
            "email",
            "mobile",
            "gender",
            "password",
            "teacher_code",
            "dept_code",
            
        ];
    }
    
    /**
    * @return array
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $genderColumn = 'F';
                $options = ['Male', 'Female'];
                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getCell("{$genderColumn}{$row}")->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Invalid Input');
                    $validation->setError('The value is not in the list.');
                    $validation->setPromptTitle('Select Gender');
                    $validation->setPrompt('Please select a gender from the dropdown list.');
                    $validation->setFormula1('"' . implode(',', $options) . '"');
                }
                $sheet->getStyle('A1:I1')->getFont()->setBold(true);
                foreach (['A', 'B', 'C', 'D', 'E','F','G','H','I'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

            },
        ];
    }
}
