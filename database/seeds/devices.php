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
        DevicesModel::create(['id' => 1, 'name' => 'Switch ATX', 'description' => '18 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 1, 'notes' => '--']);
        DevicesModel::create(['id' => 2, 'name' => 'Switch ATX54', 'description' => '54 puertos', 'status' => 'Y', 'area' => 1, 'device_type' => 1, 'notes' => '--']);
        DevicesModel::create(['id' => 3, 'name' => 'Router', 'description' => 'Capa 3', 'status' => 'Y', 'area' => 4, 'device_type' => 2, 'notes' => '--']);
        DevicesModel::create(['id' => 4, 'name' => 'Computadora', 'description' => 'Core i7 / Ram 7GB / HDD 1TB', 'status' => 'Y', 'area' => 6, 'device_type' => 3, 'notes' => 'Se reinicia']);
    }
}
