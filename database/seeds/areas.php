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
        AreasModel::create(['id' => 1, 'name' => 'Laboratorio 101', 'description' => 'Laboratorio de estudiantes']);
        AreasModel::create(['id' => 2, 'name' => 'Laboratorio 103', 'description' => 'Laboratorio de estudiantes']);
        AreasModel::create(['id' => 3, 'name' => 'Sala de docentes', 'description' => 'Docentes de Carrera']);
        AreasModel::create(['id' => 4, 'name' => 'Sala de docentes', 'description' => 'Docentes de Ciencias Basicas']);
        AreasModel::create(['id' => 5, 'name' => 'Contabilidad', 'description' => 'Area de contabilidad']);
        AreasModel::create(['id' => 6, 'name' => 'Oficina Rector', 'description' => 'Oficina Rector']);
        AreasModel::create(['id' => 7, 'name' => 'Laboratorio de redes', 'description' => 'Laboratorio Redes de Ing de Sistemas']);
    }
}
