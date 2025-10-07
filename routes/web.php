<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/employee', [EmployeeController::class, 'index'])->name('dashboard');
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::delete('/department/student/{id}', [App\Http\Controllers\DepartmentController::class, 'deleteStudent'])->name('department.student.delete');

});


require __DIR__.'/auth.php';
