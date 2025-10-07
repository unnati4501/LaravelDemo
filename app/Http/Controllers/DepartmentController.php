<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $names = $request->name; // array of names
        $errors = [];
        $success = true;
    
        foreach($names as $index => $name){
            $validator = Validator::make(['name'=>$name], [
                'name' => 'required|string|max:100|unique:departments,name',
            ]);
    
            if($validator->fails()){
                $errors["name.$index"] = $validator->errors()->get('name');
                $success = false;
            } else {
                Department::create(['name'=>$name]);
            }
        }
    
        if($success){
            return response()->json(['success'=>true]);
        } else {
            return response()->json(['errors'=>$errors], 422);
        }
    }

    public function edit(Department $department)
    {
        // Load all students for this department
        $department->load('students');

        // Pass to view
        return view('departments.edit', compact('department'));
    }


    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'students.*.name' => 'required|string|max:100',
            'students.*.degree' => 'required|string|max:100',
            'students.*.birthdate' => 'required|date',
        ]);

            // Friendly names
        $validator->setAttributeNames([
            'name' => 'Department Name',
            'students.*.name' => 'Student Name',
            'students.*.degree' => 'Degree',
            'students.*.birthdate' => 'Birthdate',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department->update(['name' => $request->name]);

        // Delete old students (optional) and insert new
        $department->students()->delete();
        if($request->students){
            foreach($request->students as $s){
                $department->students()->create($s);
            }
        }

        return response()->json(['success'=>true]);
    }

    
    public function show(Department $department)
    {
        return response()->json($department);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['success' => true]);
    }

    public function deleteStudent($id)
    {
        $student = \App\Models\Student::find($id);
        if(!$student){
            return response()->json(['success' => false, 'message' => 'Student not found']);
        }

        $student->delete();
        return response()->json(['success' => true]);
    }

}
