<?php
Route::get('/repair-status', 'Modules\Repair\Http\Controllers\CustomerRepairStatusController@index')->name('repair-status');
Route::post('/post-repair-status', 'Modules\Repair\Http\Controllers\CustomerRepairStatusController@postRepairStatus')->name('post-repair-status');
Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu'], 'prefix' => 'repair', 'namespace' => 'Modules\Repair\Http\Controllers'], function () {
    Route::get('edit-repair/{id}/status', 'RepairController@editRepairStatus');
    Route::post('update-repair-status', 'RepairController@updateRepairStatus');
    Route::get('delete-media/{id}', 'RepairController@deleteMedia');
    Route::get('print-label/{id}', 'RepairController@printLabel');
    Route::resource('/repair', 'RepairController')->except(['create', 'edit']);
    Route::resource('/status', 'RepairStatusController', ['except' => ['show']]);
    Route::resource('/guarantee', 'GuaranteeController');
    Route::any('/guarantee/get_products_sold', 'GuaranteeController@get_products_sold');
    Route::any('/guarantee/get_invoice_sold', 'GuaranteeController@get_invoice_sold');
    Route::any('/guarantee/get_supplier', 'GuaranteeController@get_supplier');
    Route::get('guarantee/{id}/status', 'GuaranteeController@editStatus');
    Route::put('guarantee-update/{id}/status', 'GuaranteeController@updateStatus');
    Route::get('guarantee/print_slim/{id}', 'GuaranteeController@print_slim');

    Route::resource('/repair-settings', 'RepairSettingsController', ['only' => ['index', 'store']]);

    Route::get('/install', 'InstallController@index');
    Route::post('/install', 'InstallController@install');
    Route::get('/install/uninstall', 'InstallController@uninstall');
    Route::get('/install/update', 'InstallController@update');

    Route::get('get-device-models', 'DeviceModelController@getDeviceModels');
    Route::get('models-repair-checklist', 'DeviceModelController@getRepairChecklists');
    Route::resource('device-models', 'DeviceModelController')->except(['show']);
    Route::resource('dashboard', 'DashboardController');
    
    Route::get('job-sheet/delete/{id}/image', 'JobSheetController@deleteJobSheetImage');
    Route::get('job-sheet/{id}/status', 'JobSheetController@editStatus');
    Route::put('job-sheet-update/{id}/status', 'JobSheetController@updateStatus');
    Route::resource('job-sheet', 'JobSheetController');
    Route::any('job-sheet/{id}/show', 'JobSheetController@show');
    Route::any('job-sheet/{id}/edit', 'JobSheetController@edit');
    Route::any('job-sheet/{id}/destroy', 'JobSheetController@destroy');
    Route::get('job-sheet/print_slim/{id}', 'JobSheetController@print_slim');
    
    // RepairController
    Route::get('/repair', 'RepairController@index')->name('repair.index');
    Route::post('/repair', 'RepairController@store')->name('repair.store');
    Route::put('/repair/{id}', 'RepairController@update')->name('repair.update');
    Route::delete('/repair/{id}', 'RepairController@destroy')->name('repair.destroy');
    
    // RepairStatusController
    Route::get('/status', 'RepairStatusController@index')->name('status.index');
    Route::post('/status', 'RepairStatusController@store')->name('status.store');
    Route::put('/status/{id}', 'RepairStatusController@update')->name('status.update');
    Route::delete('/status/{id}', 'RepairStatusController@destroy')->name('status.destroy');
    
    // GuaranteeController
    Route::get('/guarantee', 'GuaranteeController@index')->name('guarantee.index');
    Route::post('/guarantee', 'GuaranteeController@store')->name('guarantee.store');
    Route::get('/guarantee/{id}', 'GuaranteeController@show')->name('guarantee.show');
    Route::put('/guarantee/{id}', 'GuaranteeController@update')->name('guarantee.update');
    Route::delete('/guarantee/{id}', 'GuaranteeController@destroy')->name('guarantee.destroy');
    // DeviceModelController
    Route::get('device-models', 'DeviceModelController@index')->name('device-models.index');
    Route::post('device-models', 'DeviceModelController@store')->name('device-models.store');
    Route::get('device-models/{id}/edit', 'DeviceModelController@edit')->name('device-models.edit');
    Route::put('device-models/{id}', 'DeviceModelController@update')->name('device-models.update');
    Route::delete('device-models/{id}', 'DeviceModelController@destroy')->name('device-models.destroy');
    
    // DashboardController
    Route::resource('dashboard', 'DashboardController')->except(['show']);

});

