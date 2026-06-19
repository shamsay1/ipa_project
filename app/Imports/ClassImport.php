<?php

namespace App\Imports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClassImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, ToCollection
{
    use SkipsFailures;
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    public function collection(Collection $rows)
    {
        
        $classNames = [];
        foreach ($rows as $index => $row) {
            $classname = trim($row['classname']);
            if (in_array($classname, $classNames)) {
                $failure = new \Maatwebsite\Excel\Validators\Failure(
                    $index + 2, // +2 because heading row is row 1
                    'classname',
                    ['Duplicate class name in file']
                );
                $this->onFailure($failure);
            } else {
                $classNames[] = $classname;
            }
        }
    }

    public function model(array $row)
    {

        return new Room([
            "name" => $row['classname'],
            "capacity" => trim($row['capacity']),
            "type" => $row['type'],
            "practical_type" => $row['practical_type'],
            "branch_id"    => $this->branchId,

        ]);
    }

    public function rules(): array
    {
        return [
            '*.classname'      => 'required|string|max:100',
            '*.capacity'       => 'required',
            '*.type'           => 'required|string|max:50',
            '*.practical_type' => 'nullable|string|max:50',
            '*.building_code'  => 'required|exists:buildings,building_code',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.classname.required'     => 'Class name is required',
            '*.capacity.required'      => 'Capacity is required',
            '*.type.required'          => 'Type is required',
            '*.building_code.required' => 'Building code is required',
            '*.building_code.exists'   => 'Building does not exist',
        ];
    }
}
