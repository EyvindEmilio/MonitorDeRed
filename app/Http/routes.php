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
    //echo '<pre>';
    //print_r($list);
    // echo '</pre>';

    $list = explode(PHP_EOL, $list);
    //echo sizeof($list);

    $pc = array();
    for ($index = 2; $index < sizeof($list) - 3; $index += 3) {
        $mac_list = explode(' ', $list[$index + 2]);

        $mac_list = $mac_list[2];

        $ip_list = explode(' ', $list[$index]);
        $ip_list = $ip_list[4];

        array_push($pc, ['mac' => $mac_list, 'ip' => $ip_list]);
    }


    return view('welcome', ['data' => json_encode($pc)]);
}]);

Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('/users', 'UsersController');
Route::resource('/users_types', 'UsersTypesController');

/*
 *
 * Api RestFull Services
 *
 * */

Route::group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'auth'], function () {
    /** @noinspection PhpUndefinedClassInspection */
    Route::resource('/usersTypes', 'UsersTypesController');
    Route::resource('/users', 'UsersController');
});