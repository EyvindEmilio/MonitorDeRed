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

Route::get('/close', function () {
    if (!Auth::check()) return redirect()->to('login');
    if (\Illuminate\Support\Facades\Auth::user()['user_type'] == 1) {
        return redirect()->to('/');
    }
    return view('close_system');
});

/*Route::get('/last_connected', function () {
    $input = \Illuminate\Support\Facades\Input::all();
    if (isset($input['ip'])) {
        $is_data = \App\NetworkUsageModel::where('ip',ip)
    }
});*/
Route::get('/report_alerts', function () {
    $input = \Illuminate\Support\Facades\Input::all();
    if (isset($input['start_date'])) {
        return \App\Http\Controllers\ReportsController::alert($input['start_date'], $input['end_date']);
    } else {
        return \Illuminate\Http\Response::create(['error' => 0], 400);
    }
});

Route::get('/report_for_areas', function () {
    return \App\Http\Controllers\ReportsController::perAreas();
});

Route::get('/report_for_users', function () {
    return \App\Http\Controllers\ReportsController::users();
});

Route::get('/report_for_area', function () {
    $input = \Illuminate\Support\Facades\Input::all();
    if (isset($input['id'])) {
        return \App\Http\Controllers\ReportsController::perArea($input['id'], $input['start_date'], $input['end_date']);
    } else {
        return \Illuminate\Http\Response::create(['error' => 0], 400);
    }
});

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
        $number_devices_registered_connected = DB::select('SELECT COUNT(*) AS number FROM devices INNER JOIN nmap_all_scan on nmap_all_scan.ip = devices.ip')[0]->number;
        $number_devices_connected = \App\NmapAllScanModel::count();
        $number_devices_not_registered_connected = $number_devices_connected - $number_devices_registered_connected;
        $alerts_today = DB::select('SELECT * FROM `alerts` WHERE DATE(created_at) = DATE(NOW())');

        return view('dashboard/statistics', ['settings' => $settings, 'areas' => $areas, 'list_connected' => $list_connected, 'alerts_today' => $alerts_today, 'devices_connected' => ['total' => $number_devices_connected, 'registered' => $number_devices_registered_connected, 'not_registered' => $number_devices_not_registered_connected]]);
    });

    Route::get('/monitor', function () {
        $settings = \App\SettingsModel::find(1)->toArray();
        return view('dashboard/monitor', ['settings' => $settings]);
    });

    Route::get('/consumo', function () {
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        $consumo_per_areas = \App\NetworkUsageModel::getConsumo();
        $consumo_unknown = \App\NetworkUsageModel::getConsumoUnknown();
        $consumo_ip_today = DB::select("SELECT network_usage.id, network_usage.ip, network_usage.size, network_usage.date, devices.name, devices.mac FROM network_usage LEFT JOIN devices on devices.ip = network_usage.ip WHERE date = date(NOW())");
        $consumo_ip_yesterday = DB::select("SELECT network_usage.id, network_usage.ip, network_usage.size, network_usage.date, devices.name, devices.mac FROM network_usage LEFT JOIN devices on devices.ip = network_usage.ip WHERE date = date(DATE_SUB(NOW(), INTERVAL 1 DAY))");
        return view('dashboard/consumo', ['settings' => \App\SettingsModel::find(1)->toArray(), 'areas' => \App\AreasModel::all(), 'consumo_per_areas' => $consumo_per_areas, 'consumo_unknown' => $consumo_unknown, 'consumo_yesterday' => $consumo_ip_yesterday, 'consumo_today' => $consumo_ip_today]);
    });

    Route::get('/info_per_area', function () {
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        $input = \Illuminate\Support\Facades\Input::all();
        if (isset($input['id'])) {
            $consumo = \App\NetworkUsageModel::getConsumoAreaPerDate($input['id'], $input['start_date'], $input['end_date']);
            return \Illuminate\Http\Response::create($consumo);
        } else {
            return \Illuminate\Http\Response::create(['id' => 0], 400);
        }
    });

    Route::get('/getIpFromMac', function () {
        $input = \Illuminate\Support\Facades\Input::all();
        if (isset($input['ip'])) {
            $ip = strtolower($input['ip']);
            $settings = \App\SettingsModel::find(1)->toArray();
            $str_list_interfaces = shell_exec('netdiscover -NPc 10 -r ' . $settings['network_address'] . '/' . $settings['mask']);
            preg_match_all('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $str_list_interfaces, $list_ips);
            preg_match_all('/[0-9a-zA-Z]{2}:[0-9a-zA-Z]{2}:[0-9a-zA-Z]{2}:[0-9a-zA-Z]{2}:[0-9a-zA-Z]{2}:[0-9a-zA-Z]{2}/', $str_list_interfaces, $list_macs);
            $list_ips = $list_ips[0];
            $list_macs = $list_macs[0];
            $success = false;
            $mac = "";
            try {
                for ($i = 0; $i < sizeof($list_macs); $i++) {
                    if (strtolower($list_ips[$i]) == $ip) {
                        $mac = $list_macs[$i];
                        $success = true;
                        break;
                    }
                }
            } catch (Exception $e) {

            }
            return \Illuminate\Http\Response::create(['success' => $success, 'mac' => $mac]);
        } else {
            return \Illuminate\Http\Response::create(['id' => 0], 400);
        }
    });

    Route::get('/info_per_area_ip', function () {
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        $input = \Illuminate\Support\Facades\Input::all();
        if (isset($input['id'])) {
            $consumo = \App\NetworkUsageModel::getConsumoAreaIpPerDate($input['id'], $input['start_date'], $input['end_date']);
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
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        return view('devices_and_areas.device_types');
    });

    Route::get('/areas', function () {
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        return view('devices_and_areas.areas');
    });

    Route::get('/settings', function () {
        if (\App\User::isJefeOrCollaborator()) return redirect()->to('/');
        $settings = \App\SettingsModel::find(1);
        try {
            $str_list_interfaces = shell_exec('ip link show');
            preg_match_all('/: [a-zA-Z0-9]+:/', $str_list_interfaces, $list_interfaces);
            $list_interfaces = $list_interfaces[0];
            $list = [];
            for ($i = 0; $i < sizeof($list_interfaces); $i++) {
                $list_interfaces[$i];
                array_push($list, preg_replace('/[: ]+/', '', $list_interfaces[$i]));
            }
        } catch (Exception $e) {
            $list = ['eth0'];
        }
        return view('settings', ['settings' => $settings, 'interfaces' => $list]);
    });

    Route::get('/standard', function () {
        return view('standard');
    });

    Route::get('/logs', function () {
        return view('logs');
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
    Route::resource('/logs', 'LogsController');
    Route::resource('/nmap/all_scan', 'NmapAllScanController');

    Route::get('/monitor/list_status', "MonitoringController@list_status");
    Route::get('/monitor/scan_ports', "MonitoringController@scan_ports");
    Route::get('/monitor/denial_service', "MonitoringController@getDenialOfService");
});