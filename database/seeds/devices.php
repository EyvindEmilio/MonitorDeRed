<?php

use App\DevicesModel;
use Illuminate\Database\Seeder;

class devices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->delete();
        DB::table('devices')->truncate();
        DB::table('devices')->delete();
        DevicesModel::create(['id' => 1, 'name' => 'Switch Core EX4200 ', 'ip' => '192.168.1.25', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 2, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.70', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 3, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.71', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 4, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.72', 'description' => '24 puertos', 'status' => 'Y', 'area' => 2, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 5, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.73', 'description' => '24 puertos', 'status' => 'Y', 'area' => 3, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 6, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.74', 'description' => '24 puertos', 'status' => 'Y', 'area' => 4, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 7, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.75', 'description' => '24 puertos', 'status' => 'Y', 'area' => 5, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 8, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.76', 'description' => '24 puertos', 'status' => 'Y', 'area' => 6, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 9, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.77', 'description' => '24 puertos', 'status' => 'Y', 'area' => 4, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 10, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.78', 'description' => '24 puertos', 'status' => 'Y', 'area' => 2, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 11, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.79', 'description' => '24 puertos', 'status' => 'Y', 'area' => 2, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 12, 'name' => 'Router', 'ip' => '10.42.0.6', 'description' => 'Core EX4500', 'status' => 'Y', 'area' => 4, 'device_type' => 1, 'notes' => 'Area WiFi']);
        DevicesModel::create(['id' => 13, 'name' => 'Router', 'ip' => '10.42.0.7', 'description' => 'Core EX4500', 'status' => 'Y', 'area' => 4, 'device_type' => 1, 'notes' => 'Area WiFi']);
        DevicesModel::create(['id' => 14, 'name' => 'Computadora', 'ip' => '10.42.0.13', 'description' => 'PC BIBLIOTECA', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 15, 'name' => 'Computadora', 'ip' => '10.42.0.14', 'description' => 'PC REGISTROS', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 16, 'name' => 'Computadora', 'ip' => '10.42.0.15', 'description' => 'PC ADMISION', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 17, 'name' => 'Computadora', 'ip' => '10.42.0.16', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
        DevicesModel::create(['id' => 18, 'name' => 'Computadora', 'ip' => '10.42.0.17', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
        DevicesModel::create(['id' => 19, 'name' => 'Computadora', 'ip' => '10.42.0.18', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
        DevicesModel::create(['id' => 20, 'name' => 'Celular', 'ip' => '10.42.0.30', 'description' => 'Sony Adriana', 'status' => 'Y', 'area' => 3, 'device_type' => 4, 'notes' => 'Dispositivo Movil']);
        DevicesModel::create(['id' => 21, 'name' => 'Celular', 'ip' => '10.42.0.31', 'description' => 'Sony Z5', 'status' => 'Y', 'area' => 2, 'device_type' => 4, 'notes' => 'Dispositivo Movil']);
        DevicesModel::create(['id' => 22, 'name' => 'Celular', 'ip' => '10.42.0.32', 'description' => 'Sony X', 'status' => 'Y', 'area' => 2, 'device_type' => 4, 'notes' => 'Dispositivo Movil']);
        DevicesModel::create(['id' => 23, 'name' => 'Tablet', 'ip' => '10.42.0.32', 'description' => 'Samsung Claudia', 'status' => 'Y', 'area' => 1, 'device_type' => 4, 'notes' => 'Dispositivo Movil']);
        DevicesModel::create(['id' => 24, 'name' => 'Celular', 'ip' => '10.42.0.34', 'description' => 'Samsung', 'status' => 'Y', 'area' => 4, 'device_type' => 4, 'notes' => 'Dispositivo Movil']);
    }
}
