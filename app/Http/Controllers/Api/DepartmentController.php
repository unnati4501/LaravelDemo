<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json(Department::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $department = Department::create($validated);
        return response()->json(['message' => 'Department created', 'data' => $department]);
    }

    public function show($id)
    {
        $dept = Department::find($id);
        return $dept ? response()->json($dept) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $dept = Department::find($id);
        if (!$dept) return response()->json(['message' => 'Not found'], 404);

        $validated = $request->validate(['name' => 'required|string|max:255']);
        $dept->update($validated);
        return response()->json(['message' => 'Updated', 'data' => $dept]);
    }

    public function destroy($id)
    {
        $dept = Department::find($id);
        if (!$dept) return response()->json(['message' => 'Not found'], 404);

        $dept->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
