<?php 

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Api\StudentController;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/employees', [EmployeeController::class, 'index']);
//     Route::post('/employees', [EmployeeController::class, 'store']);
//     Route::post('/students', [StudentController::class, 'store']);
    
// });
Route::get('test', function () {
    return response()->json(['message' => 'Test route works!']);
});

Route::apiResource('students', StudentController::class);
