<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('/users', 'UsersController');
Route::resource('/users_types', 'UsersTypesController');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        $areas = \App\AreasModel::all();
        return view('dashboard/statistics', ['settings' => $settings, 'areas' => $areas]);
    });

    Route::get('/monitor', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        return view('dashboard/monitor', ['settings' => $settings]);
    });

    Route::get('/attacks', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        return view('dashboard/attacks', ['settings' => $settings]);
    });

    Route::get('/devices', function () {
        return view('devices_and_areas.devices');
    });
    Route::get('/device_types', function () {
        return view('devices_and_areas.device_types');
    });
    Route::get('/areas', function () {
        return view('devices_and_areas.areas');
    });
    Route::get('/settings', function () {
        $settings = \App\SettingsModel::find(1);
        return view('settings', ['settings' => $settings]);
    });
    Route::get('/standard', function () {
        return view('standard');
    });
});
/*
 *
 * Api RestFull Services
 *
 * */

Route::group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'auth'], function () {
    /** @noinspection PhpUndefinedClassInspection */
    Route::resource('/usersTypes', 'UsersTypesController');
    Route::resource('/users', 'UsersController');
    Route::resource('/areas', 'AreasController');
    Route::resource('/device_types', 'DeviceTypesController');
    Route::resource('/devices', 'DevicesController');
    Route::resource('/settings', 'SettingsController');
    Route::resource('/alerts', 'AlertsController');
    Route::resource('/nmap/all_scan', 'NmapAllScanController');

    Route::get('/monitor/list_status', "MonitoringController@list_status");
    Route::get('/monitor/scan_ports', "MonitoringController@scan_ports");
    Route::get('/monitor/denial_service', "MonitoringController@getDenialOfService");
});