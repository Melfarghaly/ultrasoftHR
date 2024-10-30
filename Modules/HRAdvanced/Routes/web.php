<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\HRAdvanced\Http\Controllers\JobController;
use Modules\HRAdvanced\Http\Controllers\TimesheetController;
use Modules\HRAdvanced\Http\Controllers\ProjectController;
use Modules\HRAdvanced\Http\Controllers\PenaltyController;

Route::middleware('web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu')->prefix('hradvanced')->group(function () {
    Route::get('/', '\Modules\HRAdvanced\Http\Controllers\HRAdvancedController@index');
    Route::get('/employee', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@index')->name('employees.index');
    Route::get('/employee/create', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@create')->name('employees.create');
    Route::post('/employee/store', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@store')->name('employees.store');
    Route::get('/employee/{id}/edit', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@edit')->name('employees.edit');
    Route::get('/employee/{id}/show', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@show')->name('employees.show');
    Route::any('/employee/{id}/destroy', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@destroy')->name('employees.destroy');
    Route::any('/employee/{id}/salaryItems', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@salaryItems')->name('employees.salaryItems');
    Route::put('/employees/{employee}/salary-items', '\Modules\HRAdvanced\Http\Controllers\EmployeeController@updateSalaryItems')->name('employee.updateSalaryItems');

    Route::any('jobs', 'Modules\HRAdvanced\Http\Controllers\JobController@index')->name('hr_jobs.index');
    Route::any('jobs/store', 'Modules\HRAdvanced\Http\Controllers\JobController@store')->name('hr_jobs.store');
    Route::any('jobs/jobStore', 'Modules\HRAdvanced\Http\Controllers\JobController@jobStore')->name('hr_jobs.jobStore');
    Route::any('jobs/{id}/edit', 'Modules\HRAdvanced\Http\Controllers\JobController@edit')->name('hr_jobs.edit');
    Route::any('jobs/{id}/show', 'Modules\HRAdvanced\Http\Controllers\JobController@show')->name('hr_jobs.show');
    Route::any('jobs/{id}/update', 'Modules\HRAdvanced\Http\Controllers\JobController@update')->name('hr_jobs.update');
    Route::any('jobs/{id}/destroy', 'Modules\HRAdvanced\Http\Controllers\JobController@destroy')->name('hr_jobs.destroy');

    //timesheets
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::post('/timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');
    Route::get('/timesheets/weekly', [TimesheetController::class, 'weekly'])->name('timesheets.weekly');
    Route::any('/timesheets/massAttend', [TimesheetController::class, 'massAttend'])->name('timesheets.massAttend');
    Route::any('/timesheets/massApproved', [TimesheetController::class, 'massApproved'])->name('timesheets.massApproved');
    Route::any('/timesheets/massPosted', [TimesheetController::class, 'massPosted'])->name('timesheets.massPosted');
    Route::get('/timesheets/daily', [TimesheetController::class, 'daily'])->name('timesheets.daily');
    Route::get('/timesheets/monthly', [TimesheetController::class, 'monthly'])->name('timesheets.monthly');

    //projects

    Route::any('projects', 'Modules\HRAdvanced\Http\Controllers\ProjectController@index')->name('hr_projects.index');
    Route::any('projects/create', 'Modules\HRAdvanced\Http\Controllers\ProjectController@create')->name('hr_projects.create');
    Route::any('projects/store', 'Modules\HRAdvanced\Http\Controllers\ProjectController@store')->name('hr_projects.store');
    Route::any('projects/{id}/edit', 'Modules\HRAdvanced\Http\Controllers\ProjectController@edit')->name('hr_projects.edit');
    Route::any('projects/{id}/show', 'Modules\HRAdvanced\Http\Controllers\ProjectController@show')->name('hr_projects.show');
    Route::any('projects/{id}/update', 'Modules\HRAdvanced\Http\Controllers\ProjectController@update')->name('hr_projects.update');
    Route::any('projects/{id}/destroy', 'Modules\HRAdvanced\Http\Controllers\ProjectController@destroy')->name('hr_projects.destroy');

    //penalties

    Route::any('penalties', [PenaltyController::class, 'index'])->name('penalties.index');
    Route::any('penalties/create', [PenaltyController::class, 'create'])->name('penalties.create');
    Route::any('penalties/store', [PenaltyController::class, 'store'])->name('penalties.store');
    Route::any('penalties/{id}/edit', [PenaltyController::class, 'edit'])->name('penalties.edit');
    Route::any('penalties/{id}/show', [PenaltyController::class, 'show'])->name('penalties.show');
    Route::any('penalties/{id}/update', [PenaltyController::class, 'update'])->name('penalties.update');
    Route::any('penalties/{id}/destroy', 'Modules\HRAdvanced\Http\Controllers\PenaltyController@destroy')->name('penalties.destroy');
});
