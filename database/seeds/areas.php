<?php

use App\AreasModel;
use Illuminate\Database\Seeder;

class areas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->delete();
        DB::table('areas')->truncate();
        DB::table('areas')->delete();
        AreasModel::create(['id' => 1, 'name' => 'Area de infortmatica', 'description' => '']);
        AreasModel::create(['id' => 2, 'name' => 'Area de Contabilidad', 'description' => '']);
        AreasModel::create(['id' => 3, 'name' => 'Area de Administracion', 'description' => '']);
        AreasModel::create(['id' => 4, 'name' => 'Area de Admision', 'description' => '']);
        AreasModel::create(['id' => 5, 'name' => 'Laboratorios', 'description' => '']);
        AreasModel::create(['id' => 6, 'name' => 'Secreteria', 'description' => '']);
        AreasModel::create(['id' => 7, 'name' => 'Biblioteca', 'description' => '']);
    }
}
