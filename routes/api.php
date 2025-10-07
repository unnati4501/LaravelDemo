<?php 

use App\Http\Controllers\EmployeeController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
});
