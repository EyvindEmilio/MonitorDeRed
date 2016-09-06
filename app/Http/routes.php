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


use Dompdf\Dompdf;

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('/users', 'UsersController');
Route::resource('/users_types', 'UsersTypesController');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        $areas = \App\AreasModel::all();
        $device_types = \App\DeviceTypesModel::all()->toArray();

        $list_connected = array();
        for ($i = 0; $i < sizeof($device_types); $i++) {
            $connected = DB::select('SELECT COUNT(*) As connected, (SELECT COUNT(*) FROM devices WHERE devices.device_type = ' . $device_types[$i]['id'] . ') AS total from devices INNER JOIN nmap_all_scan on nmap_all_scan.ip = devices.ip INNER JOIN device_types on device_types.id = devices.device_type WHERE devices.device_type = ' . $device_types[$i]['id']);
            $connected = $connected [0];
            $connected->device_type = $device_types[$i]['name'];
            $connected->image = 'http://' . $_SERVER['HTTP_HOST'] . '/images/device_types/' . $device_types[$i]['image'];
            array_push($list_connected, $connected);
        }

        $alerts_today = DB::select('SELECT * FROM `alerts` WHERE DATE(created_at) = DATE(NOW())');

        return view('dashboard/statistics', ['settings' => $settings, 'areas' => $areas, 'list_connected' => $list_connected, 'alerts_today' => $alerts_today]);
    });

    Route::get('/monitor', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        return view('dashboard/monitor', ['settings' => $settings]);
    });

    Route::get('/consumo', function () {
        $consumo_per_areas = DB::select('SELECT  (SELECT SUM((SELECT SUM(network_usage.size) AS network_usage FROM network_usage WHERE network_usage.ip = devices.ip)) as network_usage from devices WHERE area = areas.id) AS network_usage ,  areas.name as area, areas.id AS id FROM areas');
        return view('dashboard/consumo', ['settings' => \App\SettingsModel::find(1)->toArray(), 'areas' => \App\AreasModel::all(), 'consumo_per_areas' => $consumo_per_areas]);
    });

    Route::get('/test_pdf', function () {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('<h1>hello world</h1>');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte', ['Attachment' => 0]);
    });

    Route::get('/info_per_area', function () {
        $input = \Illuminate\Support\Facades\Input::all();
        if (isset($input['id'])) {
            $id = $input['id'];//id_area

            $sd = $input['start_date'];
            $ed = $input['end_date'];

            $start_date = new DateTime($sd);
            $end_date = (new DateTime($ed))->modify('+1 day');
            $date_range = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
            $consumo = [];
            foreach ($date_range as $date) {
                $current_date = $date->format('Y-m-d');
                $size = \App\NetworkUsageModel::where('date', $current_date)
                    ->join('devices', 'devices.ip', '=', 'network_usage.ip')
                    ->where('devices.area', $id)->sum('size');
                array_push($consumo, ['date' => $current_date, 'size' => $size]);
            }
            return \Illuminate\Http\Response::create($consumo);
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