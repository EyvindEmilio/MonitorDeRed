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

    Route::get('/consumo', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        $areas = \App\AreasModel::all();
        $consumo_per_areas = DB::select('SELECT  (SELECT SUM((SELECT SUM(network_usage.size) AS network_usage FROM network_usage WHERE network_usage.ip = devices.ip)) as network_usage from devices WHERE area = areas.id) AS network_usage ,  areas.name as area, areas.id AS id FROM areas');
        //print_r($consumo_per_areas[1]);
        return view('dashboard/consumo', ['settings' => $settings, 'areas' => $areas, 'consumo_per_areas' => $consumo_per_areas]);
    });

    Route::get('/info_per_area', function () {
        $input = \Illuminate\Support\Facades\Input::all();
        if (isset($input['id'])) {
            $id = $input['id'];
            $days_comsumo = [];
            for ($i = 0; $i < 7; $i++) {
                $consumo_area = DB::select('SELECT date(now() - INTERVAL ' . $i . ' DAY) as date, network_usage.ip, network_usage.size, devices.name, areas.name AS area, areas.id AS area_id, (SELECT device_types.name FROM device_types WHERE device_types.id = devices.device_type) as type FROM network_usage LEFT JOIN devices ON devices.ip = network_usage.ip LEFT JOIN areas ON areas.id = devices.area WHERE network_usage.date = date(now() - INTERVAL ' . $i . ' DAY) AND areas.id = ' . $id);
//                $consumo_area['date']
                array_push($days_comsumo, $consumo_area);
            }
            return \Illuminate\Http\Response::create($days_comsumo);
        } else {
            return \Illuminate\Http\Response::create(['id' => 0], 400);
        }
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