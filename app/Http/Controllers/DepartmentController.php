<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Loggins;

class DepartmentController extends Controller
{
    public function index(){
        $depts = Department::all();
            return view("department",compact("depts"));
    }
    public function store(Request $request){
            $request->validate([
                "deptName" => "required",
                "dept_code" => "required"
            ]);
            $dept = Department::create([
                "deptName" => $request->deptName,
                "dept_code" => $request->dept_code
            ]);
            if($dept){
                Loggins::create([
                'title' => 'New Registration',
                'action' => $request->deptName.' is registered',
                ]);
                return redirect()->back()->with("success","New Department is Registered Successfully");
            }

        }
        public function edit($id){
            $dept = Department::findOrFail($id);
            return view("departmentEdit",compact("dept"));
        }
        public function update(Request $request,$id){
            $request->validate([
                "deptName" => "required",
                "dept_code" => "required"
            ]
            );
            $dept = Department::findOrFail($id);
            $dept->update([
                "deptName" => $request->deptName,
                "dept_code" => $request->dept_code,
            ]);
            Loggins::create([
            'title' => 'New Department Updates',
            'action' => 'The '.$dept->deptName.' is updating',
            ]);
            return redirect()->route("department.index")->with("success","Department updated success");
        }
        public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return redirect()->route('department.index')->with('error', 'Department not found.');
        }
        Loggins::create([
            'title' => 'New Deletings',
            'action' => 'The '.$department->deptName.' is is deleting',
            ]);

        $department->delete();

        return redirect()->route('department.index')->with('success', 'Department deleted successfully.');
    }
}
