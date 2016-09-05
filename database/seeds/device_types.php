<?php

use App\DeviceTypesModel;
use Illuminate\Database\Seeder;

class device_types extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_types')->delete();
        DB::table('device_types')->truncate();
        DB::table('device_types')->delete();
        DeviceTypesModel::create(['id' => 1, 'router.png' => '', 'name' => 'Switch', 'description' => 'Switch Core EX4200', 'manufacturer' => 'Juniper']);
        DeviceTypesModel::create(['id' => 2, 'switch.png' => '', 'name' => 'Router', 'description' => 'Switch Core EX4500 ', 'manufacturer' => 'Juniper']);
        DeviceTypesModel::create(['id' => 3, 'computador.png' => '', 'name' => 'Computador', 'description' => '--', 'manufacturer' => 'Delux']);
        DeviceTypesModel::create(['id' => 4, 'celular.png' => '', 'name' => 'Dispositivos Moviles', 'description' => 'Celular/Tablets', 'manufacturer' => 'Companys']);
    }
}
