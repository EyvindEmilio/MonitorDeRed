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
        DeviceTypesModel::create(['id' => 1, 'image' => '', 'name' => 'Switch', 'description' => 'Switch capa 3', 'manufacturer' => 'Cisco']);
        DeviceTypesModel::create(['id' => 2, 'image' => '', 'name' => 'Router', 'description' => '--', 'manufacturer' => 'Cisco']);
        DeviceTypesModel::create(['id' => 3, 'image' => '', 'name' => 'Computadora', 'description' => '--', 'manufacturer' => 'TP-LINK']);
    }
}
