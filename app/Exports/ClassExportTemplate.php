<?php

namespace App\Exports;
use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ClassExportTemplate implements FromCollection, WithHeadings, WithEvents
{
    /**
     * Return empty collection (template only)
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([]);
    }

    /**
     * Headings for template
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            "classname",       // A
            "capacity",
            "building_code",        // B (dropdown: Normal, Hall)
            "type",       // D
            "practical_type"   // E (dropdown: Computer, Work shop, Mechanics, Civil lab)
        ];
    }

    /**
     * Register AfterSheet events to add data validation (dropdowns)
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- validation for "capacity" column (B) ---
                $capacityValidation = new DataValidation();
                $capacityValidation->setType(DataValidation::TYPE_LIST);
                $capacityValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $capacityValidation->setAllowBlank(false);
                $capacityValidation->setShowInputMessage(true);
                $capacityValidation->setShowErrorMessage(true);
                $capacityValidation->setShowDropDown(true);
                $capacityValidation->setFormula1('"Normal,Hall"');

                // --- validation for "type" column (C) ---
                $typeValidation = new DataValidation();
                $typeValidation->setType(DataValidation::TYPE_LIST);
                $typeValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $typeValidation->setAllowBlank(false);
                $typeValidation->setShowInputMessage(true);
                $typeValidation->setShowErrorMessage(true);
                $typeValidation->setShowDropDown(true);
                $typeValidation->setFormula1('"Lab,Normal"');

                // --- validation for "practical_type" column (E) ---
                $practicalValidation = new DataValidation();
                $practicalValidation->setType(DataValidation::TYPE_LIST);
                $practicalValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $practicalValidation->setAllowBlank(true); // allow blank for Normal rooms
                $practicalValidation->setShowInputMessage(true);
                $practicalValidation->setShowErrorMessage(true);
                $practicalValidation->setShowDropDown(true);
                $practicalValidation->setFormula1('"Normal,Computer Lab,Electronics Lab,Engineering Drawing Lab,Civil Materials Lab,Clinical Chemistry Lab,Biochemistry Lab,Microbiology Lab"');


                // Apply validations for rows 2..1000
                for ($i = 2; $i <= 1000; $i++) {
                    // Column B = capacity
                    $sheet->getCell("B{$i}")->setDataValidation(clone $capacityValidation);
                    // Column C = type
                    $sheet->getCell("D{$i}")->setDataValidation(clone $typeValidation);
                    // Column E = practical_type
                    $sheet->getCell("E{$i}")->setDataValidation(clone $practicalValidation);
                }

                // Cosmetic: bold headers and autosize columns
                $sheet->getStyle('A1:E1')->getFont()->setBold(true);
                foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}
