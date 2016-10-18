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
        DevicesModel::create(['id' => 1, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.4', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 2, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.5', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 3, 'name' => 'Switch Core EX4200 ', 'ip' => '10.42.0.6', 'description' => '48 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 2, 'notes' => 'Area Local']);
        DevicesModel::create(['id' => 12, 'name' => 'Router', 'ip' => '10.42.0.7', 'description' => 'Core EX4500', 'status' => 'Y', 'area' => 4, 'device_type' => 1, 'notes' => 'Area WiFi']);
        DevicesModel::create(['id' => 13, 'name' => 'Router', 'ip' => '10.42.0.8', 'description' => 'Core EX4500', 'status' => 'Y', 'area' => 4, 'device_type' => 1, 'notes' => 'Area WiFi']);
        DevicesModel::create(['id' => 14, 'name' => 'Computadora', 'ip' => '10.42.0.9', 'description' => 'PC BIBLIOTECA', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 15, 'name' => 'Computadora', 'ip' => '10.42.0.10', 'description' => 'PC REGISTROS', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 16, 'name' => 'Computadora', 'ip' => '10.42.0.11', 'description' => 'PC ADMISION', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area DMZ']);
        DevicesModel::create(['id' => 17, 'name' => 'Computadora', 'ip' => '10.42.0.12', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
        DevicesModel::create(['id' => 18, 'name' => 'Computadora', 'ip' => '10.42.0.13', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
        DevicesModel::create(['id' => 19, 'name' => 'Computadora', 'ip' => '10.42.0.14', 'description' => 'PC ACADEMICO', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Area']);
    }
}
