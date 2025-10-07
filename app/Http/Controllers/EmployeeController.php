<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $departmentId = $request->query('department_id');

        $query = Employee::with('department');

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->paginate(10);
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments', 'search', 'departmentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->photo->store('employees', 'public');
        }

        Employee::create($validated);
        return response()->json(['success' => true, 'message' => 'Employee added successfully!']);
    }

    public function edit(Employee $employee)
{
    $departments = Department::all();
    return view('employees.edit', compact('employee', 'departments'));
}

public function update(Request $request, Employee $employee)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:employees,email,' . $employee->id,
        'department_id' => 'required|exists:departments,id',
        'phone' => 'nullable|string|max:20',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $request->only('name', 'email', 'department_id', 'phone');

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('employees', 'public');
    }

    $employee->update($data);

    return response()->json(['success' => true, 'message' => 'Employee updated successfully']);
}


    public function destroy(Employee $employee)
    {
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->delete();
        return response()->json(['success' => true]);
    }
}
