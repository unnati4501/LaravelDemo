<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DepartmentController;

 Route::post('register', [AuthApiController::class, 'register']);
 Route::post('login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('departments', DepartmentController::class);
});