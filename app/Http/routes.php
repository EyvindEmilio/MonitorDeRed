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

Route::get('/', ['middleware' => 'auth', 'uses' => function () {
    $list = shell_exec('nmap -sP 192.168.1.*');
    $list = explode(PHP_EOL, $list);

    $pc = array();
    for ($index = 2; $index < sizeof($list) - 4; $index += 3) {
        $first_line = explode(' ', $list[$index + 2], 4);
        $mac = $first_line[2];
        $manufacturer = $first_line[3];

        $second_line = explode(' ', $list[$index]);
        $ip = $second_line[4];

        array_push($pc, ['mac' => $mac, 'ip' => $ip, 'manufacturer' => $manufacturer]);
    }


    return view('welcome', ['data' => json_encode($pc)]);
}]);

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('/users', 'UsersController');
Route::resource('/users_types', 'UsersTypesController');

Route::group(['middleware' => 'auth'], function () {
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
        return view('settings',['settings'=>$settings]);
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
});