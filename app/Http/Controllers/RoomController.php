<?php

namespace App\Http\Controllers;

use App\Exports\ClassExportTemplate;
use Illuminate\Http\Request;
use App\Models\Room;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClassImport;
use App\Models\Building;
use App\Models\Loggins;

class RoomController extends Controller
{
  public function classImport(Request $request)
{
    $request->validate([
        'class_file' => 'required|file|mimes:xlsx,csv',
    ], [
        'class_file.required' => 'Please upload a file',
        'class_file.mimes'    => 'Only Excel files (xlsx, csv) are allowed',
    ]);

    $file = $request->file('class_file');

    // 1️⃣ Check headers first
    $expectedHeaders = ['classname', 'capacity', 'building_code', 'type', 'practical_type'];

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();
    $headerRow = $sheet->rangeToArray('A1:E1', NULL, TRUE, TRUE, TRUE)[1];

    $uploadedHeaders = array_map('strtolower', array_values($headerRow));

    if ($uploadedHeaders !== $expectedHeaders) {
        return back()->withErrors(['class_file' => 'File is not match']);
    }

    $import = new ClassImport;

    try {
        Excel::import($import, $file);
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        $errors = [];
        foreach ($failures as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    } catch (\Exception $e) {
        return back()->withErrors(['class_file' => 'File is not match']);
    }

    if ($import->failures()->isNotEmpty()) {
        $errors = [];
        foreach ($import->failures() as $failure) {
            $errors[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
        }
        return back()->withErrors($errors);
    }
    Loggins::create([
        'title' => 'New Class Registration',
        'action' => 'New classrooms are registered',
    ]);

    return redirect()->back()->with("success","Excel file of Classrooms is inserted successfully");
}
    public function index(Request $request)
{
    $query = Room::query(); 

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'LIKE', "%$search%");
    }

    $rooms = $query->paginate(10)->withQueryString();
    $buildings = Building::all();

    // Ikiwa ni AJAX request, rudisha partial view tu
    if ($request->ajax()) {
        return view('partials.room_table', compact('rooms'))->render();
    }

    return view('class', compact('rooms', 'buildings'));
}
         public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->route('room.index')->with('error', 'Room not found.');
        }

        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }

        public function store(Request $request){

            $request->validate([
                "classname" => "required",
                "capacity" => "required",
                "type" => "required",
                "practical_type" => "required",
                "building_id" => "required"
            ]);
            $new_room = Room::create([
                "name" => $request->classname,
                "capacity" => $request->capacity,
                "type" => $request->type,
                "practical_type" => $request->practical_type,
                "building_id" => $request->building_id,
            ]);
            if($new_room){
                return redirect()->back()->with("success","New classroom is added sucessfully");
            }
        }
        public function edit($id){
            $room = Room::findOrFail($id);
            return view("roomEdit",compact("room"));
        }
        public function update(Request $request,$id){
            $request->validate([
                "name" => "required",
                "capacity" => "required",
                "type" => "required",
                "practical_type" => "required",
            ]);
            $room = Room::findOrFail($id);
            $room->update($request->all());
            return redirect()->route("room.index")->with("success","Classroom is updated sucess");
        }
        public function roomTemplate(){
            return Excel::download(new ClassExportTemplate,"class_entry_format.xlsx");
        }
}
