<?php

use App\user_type_model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class users_types_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_types')->delete();
        DB::table('users_types')->truncate();
        DB::table('users_types')->delete();
        user_type_model::create(['id' => 1, 'name' => 'Administrador', 'description' => 'Usuario Administrador']);
        user_type_model::create(['id' => 2, 'name' => 'Colaborador', 'description' => 'Usuario Colaborador']);
        user_type_model::create(['id' => 3, 'name' => 'Jefe', 'description' => 'Usuario jefe']);
    }
}
