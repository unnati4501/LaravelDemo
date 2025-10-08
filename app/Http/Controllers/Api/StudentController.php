<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Include department info
        $students = Student::with('department')->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'degree'        => 'required|string|max:255',
            'birthdate'     => 'required|date',
            'department_id' => 'required|exists:departments,id',
        ]);

        $student = Student::create($validated);
        return response()->json(['message' => 'Student created', 'data' => $student], 201);
    }

    public function show($id)
    {
        $student = Student::with('department')->find($id);
        return $student ? response()->json($student) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) return response()->json(['message' => 'Not found'], 404);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'birthdate'     => 'required|date',
            'department_id' => 'required|exists:departments,id',
        ]);

        $student->update($validated);
        return response()->json(['message' => 'Updated successfully', 'data' => $student]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) return response()->json(['message' => 'Not found'], 404);

        $student->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
