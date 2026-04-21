<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Room;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Course_RoomsExport implements WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'courseName',
            'nta_level',
            'class_name',
            'total_students'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function($event) {

                $sheet = $event->sheet->getDelegate();

                // ===== STYLE HEADER =====
                $sheet->getStyle('A1:D1')->getFont()->setBold(true);

                foreach (['A', 'B', 'C','D'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $courses = Course::whereNotNull('short_name')
                ->where('short_name', '!=', '')
                ->get()
                ->map(function($course){
                    return $course->short_name . '-' . $course->course_level;
                })
                ->toArray();
                $ntas = ['NTA-4', 'NTA-5', 'NTA-6', 'NTA-7', 'NTA-8'];

                $rooms = Room::whereNotNull('name')
                            ->pluck('name')
                            ->toArray();
                $courseList = '"' . implode(',', $courses) . '"';
                $ntaList = '"' . implode(',', $ntas) . '"';
                $roomList = '"' . implode(',', $rooms) . '"';

                for ($i = 2; $i <= 200; $i++) {
                    $sheet->getCell("A$i")->getDataValidation()
                        ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                        ->setFormula1($courseList)
                        ->setShowDropDown(true);

                    // NTA dropdown
                    $sheet->getCell("B$i")->getDataValidation()
                        ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                        ->setFormula1($ntaList)
                        ->setShowDropDown(true);

                    // Room dropdown
                    $sheet->getCell("C$i")->getDataValidation()
                        ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                        ->setFormula1($roomList)
                        ->setShowDropDown(true);
                }
            }
        ];
    }
}