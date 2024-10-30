<?php

Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu'], 'namespace' => 'Modules\Essentials\Http\Controllers'], function () {
    Route::group(['prefix' => 'essentials'], function () {
        
        Route::get('/dashboard', 'DashboardController@essentialsDashboard');
        Route::get('/install', 'InstallController@index');
        Route::get('/install/update', 'InstallController@update');
        Route::get('/install/uninstall', 'InstallController@uninstall');
        
        Route::get('/', 'EssentialsController@index');

        //document controller
        Route::resource('document', 'DocumentController')->only(['index', 'store', 'destroy', 'show']);
        Route::get('document/download/{id}', 'DocumentController@download');
        // Route for 'document-share' edit action with {id}
        Route::get('document-share/{id}/edit', 'DocumentShareController@edit')->name('document-share.edit');
        
        // Route for 'document-share' update action with {id}
        Route::put('document-share/{id}', 'DocumentShareController@update')->name('document-share.update');

        //document share controller
        Route::resource('document-share', 'DocumentShareController')->only(['edit', 'update']);
        // Route for 'document' index action
        Route::get('document', 'DocumentController@index')->name('document.index');
        
        // Route for 'document' store action
        Route::post('document', 'DocumentController@store')->name('document.store');
        
        // Route for 'document' show action with {id}
        Route::get('document/{id}', 'DocumentController@show')->name('document.show');
        
        // Route for 'document' destroy action with {id}
        Route::delete('document/{id}', 'DocumentController@destroy')->name('document.destroy');

        //todo controller
        // Routes for 'todo' resource
        Route::get('todo', 'ToDoController@index')->name('todo.index');
        Route::get('todo/create', 'ToDoController@create')->name('todo.create');
        Route::post('todo', 'ToDoController@store')->name('todo.store');
        Route::get('todo/{id}', 'ToDoController@show')->name('todo.show');
        Route::get('todo/{id}/edit', 'ToDoController@edit')->name('todo.edit');
        Route::put('todo/{id}', 'ToDoController@update')->name('todo.update');
        Route::delete('todo/{id}', 'ToDoController@destroy')->name('todo.destroy');


        Route::post('todo/add-comment', 'ToDoController@addComment');
        Route::get('todo/delete-comment/{id}', 'ToDoController@deleteComment');
        Route::get('todo/delete-document/{id}', 'ToDoController@deleteDocument');
        Route::post('todo/upload-document', 'ToDoController@uploadDocument');
         Route::get('view-todo-{id}-share-docs', 'ToDoController@viewSharedDocs');

        Route::any('todo/store_status', 'ToDoController@store_status');
        Route::any('todo/meeting_status/{id}', 'ToDoController@delet_meeting_status');

        //reminder controller
        Route::resource('reminder', 'ReminderController')->only(['index', 'store', 'edit', 'update', 'destroy', 'show']);
        // Route for 'reminder' index action
        Route::get('reminder', 'ReminderController@index')->name('reminder.index');
        
        // Route for 'reminder' store action
        Route::post('reminder', 'ReminderController@store')->name('reminder.store');
        
        // Route for 'reminder' edit action with {id}
        Route::get('reminder/{id}/edit', 'ReminderController@edit')->name('reminder.edit');
        
        // Route for 'reminder' update action with {id}
        Route::put('reminder/{id}', 'ReminderController@update')->name('reminder.update');
        
        // Route for 'reminder' destroy action with {id}
        Route::delete('reminder/{id}', 'ReminderController@destroy')->name('reminder.destroy');
        
        // Route for 'reminder' show action with {id}
        Route::get('reminder/{id}', 'ReminderController@show')->name('reminder.show');

        //message controller
        Route::get('get-new-messages', 'EssentialsMessageController@getNewMessages');
        Route::resource('messages', 'EssentialsMessageController')->only(['index', 'store','destroy']);
        // Route for 'messages' index action
        Route::get('messages', 'EssentialsMessageController@index')->name('messages.index');
        
        // Route for 'messages' store action
        Route::post('messages', 'EssentialsMessageController@store')->name('messages.store');
        
        // Route for 'messages' destroy action with {id}
        Route::delete('messages/{id}', 'EssentialsMessageController@destroy')->name('messages.destroy');

        //Allowance and deduction controller
        Route::resource('allowance-deduction', 'EssentialsAllowanceAndDeductionController');

        Route::resource('knowledge-base', 'KnowledgeBaseController');

        Route::get('user-sales-targets', 'DashboardController@getUserSalesTargets');
        // Routes for 'allowance-deduction' resource
        Route::get('allowance-deduction', 'EssentialsAllowanceAndDeductionController@index')->name('allowance-deduction.index');
        Route::get('allowance-deduction/create', 'EssentialsAllowanceAndDeductionController@create')->name('allowance-deduction.create');
        Route::post('allowance-deduction', 'EssentialsAllowanceAndDeductionController@store')->name('allowance-deduction.store');
        Route::get('allowance-deduction/{id}', 'EssentialsAllowanceAndDeductionController@show')->name('allowance-deduction.show');
        Route::get('allowance-deduction/{id}/edit', 'EssentialsAllowanceAndDeductionController@edit')->name('allowance-deduction.edit');
        Route::put('allowance-deduction/{id}', 'EssentialsAllowanceAndDeductionController@update')->name('allowance-deduction.update');
        Route::delete('allowance-deduction/{id}', 'EssentialsAllowanceAndDeductionController@destroy')->name('allowance-deduction.destroy');

        // Routes for 'knowledge-base' resource
        Route::get('knowledge-base', 'KnowledgeBaseController@index')->name('knowledge-base.index');
        Route::get('knowledge-base/create', 'KnowledgeBaseController@create')->name('knowledge-base.create');
        Route::post('knowledge-base', 'KnowledgeBaseController@store')->name('knowledge-base.store');
        Route::get('knowledge-base/{id}', 'KnowledgeBaseController@show')->name('knowledge-base.show');
        Route::get('knowledge-base/{id}/edit', 'KnowledgeBaseController@edit')->name('knowledge-base.edit');
        Route::put('knowledge-base/{id}', 'KnowledgeBaseController@update')->name('knowledge-base.update');
        Route::delete('knowledge-base/{id}', 'KnowledgeBaseController@destroy')->name('knowledge-base.destroy');

    });

    Route::group(['prefix' => 'hrm'], function () {
        Route::get('/dashboard', 'DashboardController@hrmDashboard');
        Route::resource('/leave-type', 'EssentialsLeaveTypeController');
        Route::resource('/leave', 'EssentialsLeaveController');
        // Routes for 'leave-type' resource
        Route::get('/leave-type', 'EssentialsLeaveTypeController@index')->name('leave-type.index');
        Route::get('/leave-type/create', 'EssentialsLeaveTypeController@create')->name('leave-type.create');
        Route::post('/leave-type', 'EssentialsLeaveTypeController@store')->name('leave-type.store');
        Route::get('/leave-type/{id}', 'EssentialsLeaveTypeController@show')->name('leave-type.show');
        Route::get('/leave-type/{id}/edit', 'EssentialsLeaveTypeController@edit')->name('leave-type.edit');
        Route::put('/leave-type/{id}', 'EssentialsLeaveTypeController@update')->name('leave-type.update');
        Route::delete('/leave-type/{id}', 'EssentialsLeaveTypeController@destroy')->name('leave-type.destroy');
        
        // Routes for 'leave' resource
        Route::get('/leave', 'EssentialsLeaveController@index')->name('leave.index');
        Route::get('/leave/create', 'EssentialsLeaveController@create')->name('leave.create');
        Route::post('/leave', 'EssentialsLeaveController@store')->name('leave.store');
        Route::get('/leave/{id}', 'EssentialsLeaveController@show')->name('leave.show');
        Route::get('/leave/{id}/edit', 'EssentialsLeaveController@edit')->name('leave.edit');
        Route::put('/leave/{id}', 'EssentialsLeaveController@update')->name('leave.update');
        Route::delete('/leave/{id}', 'EssentialsLeaveController@destroy')->name('leave.destroy');

        Route::post('/change-status', 'EssentialsLeaveController@changeStatus');
        Route::get('/leave/activity/{id}', 'EssentialsLeaveController@activity');
        Route::get('/user-leave-summary', 'EssentialsLeaveController@getUserLeaveSummary');

        Route::get('/settings', 'EssentialsSettingsController@edit');
        Route::post('/settings', 'EssentialsSettingsController@update');

        Route::post('/import-attendance', 'AttendanceController@importAttendance');
        Route::resource('/attendance', 'AttendanceController');
        Route::post('/clock-in-clock-out', 'AttendanceController@clockInClockOut');

        Route::post('/validate-clock-in-clock-out', 'AttendanceController@validateClockInClockOut');

        Route::get('/get-attendance-by-shift', 'AttendanceController@getAttendanceByShift');
        Route::get('/get-attendance-by-date', 'AttendanceController@getAttendanceByDate');
        Route::get('/get-attendance-row/{user_id}', 'AttendanceController@getAttendanceRow');

        Route::get(
            '/user-attendance-summary',
            'AttendanceController@getUserAttendanceSummary'
        );

        Route::get('/location-employees', 'PayrollController@getEmployeesBasedOnLocation');
        Route::get('/my-payrolls', 'PayrollController@getMyPayrolls');
        Route::get('/get-allowance-deduction-row', 'PayrollController@getAllowanceAndDeductionRow');
        Route::get('/payroll-group-datatable', 'PayrollController@payrollGroupDatatable');
        Route::get('/view/{id}/payroll-group', 'PayrollController@viewPayrollGroup');
        Route::get('/edit/{id}/payroll-group', 'PayrollController@getEditPayrollGroup');
        Route::post('/update-payroll-group', 'PayrollController@getUpdatePayrollGroup');
        Route::get('/payroll-group/{id}/add-payment', 'PayrollController@addPayment');
        Route::post('/post-payment-payroll-group', 'PayrollController@postAddPayment');
        Route::resource('/payroll', 'PayrollController');
        Route::resource('/holiday', 'EssentialsHolidayController');
        // Routes for 'payroll' resource
        Route::get('/payroll', 'PayrollController@index')->name('payroll.index');
        Route::get('/payroll/create', 'PayrollController@create')->name('payroll.create');
        Route::post('/payroll', 'PayrollController@store')->name('payroll.store');
        Route::get('/payroll/{id}', 'PayrollController@show')->name('payroll.show');
        Route::get('/payroll/{id}/edit', 'PayrollController@edit')->name('payroll.edit');
        Route::put('/payroll/{id}', 'PayrollController@update')->name('payroll.update');
        Route::delete('/payroll/{id}', 'PayrollController@destroy')->name('payroll.destroy');
        
        // Routes for 'holiday' resource
        Route::get('/holiday', 'EssentialsHolidayController@index')->name('holiday.index');
        Route::get('/holiday/create', 'EssentialsHolidayController@create')->name('holiday.create');
        Route::post('/holiday', 'EssentialsHolidayController@store')->name('holiday.store');
        Route::get('/holiday/{id}', 'EssentialsHolidayController@show')->name('holiday.show');
        Route::get('/holiday/{id}/edit', 'EssentialsHolidayController@edit')->name('holiday.edit');
        Route::put('/holiday/{id}', 'EssentialsHolidayController@update')->name('holiday.update');
        Route::delete('/holiday/{id}', 'EssentialsHolidayController@destroy')->name('holiday.destroy');

        Route::get('/shift/assign-users/{shift_id}', 'ShiftController@getAssignUsers');
        Route::post('/shift/assign-users', 'ShiftController@postAssignUsers');
        Route::resource('/shift', 'ShiftController');
        Route::get('/sales-target', 'SalesTargetController@index');
        Route::get('/set-sales-target/{id}', 'SalesTargetController@setSalesTarget');
        
        Route::post('/save-sales-target', 'SalesTargetController@saveSalesTarget');
        
        // Routes for 'shift' resource
        Route::get('/shift', 'ShiftController@index')->name('shift.index');
        Route::get('/shift/create', 'ShiftController@create')->name('shift.create');
        Route::post('/shift', 'ShiftController@store')->name('shift.store');
        Route::get('/shift/{id}', 'ShiftController@show')->name('shift.show');
        Route::get('/shift/{id}/edit', 'ShiftController@edit')->name('shift.edit');
        Route::put('/shift/{id}', 'ShiftController@update')->name('shift.update');
        Route::delete('/shift/{id}', 'ShiftController@destroy')->name('shift.destroy');

    });
});
